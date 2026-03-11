<?php
// src/Controller/DoctrineTestController.php
namespace App\Controller;

use App\Repository\AutorRepository;
use App\Repository\LibroRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DoctrineConsultasController extends AbstractController
{
    #[Route('/doctrine/{entity}/{method}/{param1?}/{param2?}', name: 'doctrine_test')]
    public function index(
        string $entity,
        string $method,
        ?string $param1,
        ?string $param2,
        AutorRepository $autorRepository,
        LibroRepository $libroRepository
    ): Response {
        $result = null;
        $error = null;

        try {
            if ($entity === 'libro') {
                switch ($method) {
                    case 'findLibrosSuperVentas':
                        $result = $libroRepository->findLibrosSuperVentas((float) $param1);
                        break;
                    //QB: Con QueryBuilder
                    case 'findLibrosSuperVentasQB':
                        $result = $libroRepository->findLibrosSuperVentasQB($param1 ?? '0');
                        break;
                    case 'findLibrosPorEditorial':
                        $result = $libroRepository->findLibrosPorEditorial($param1 ?? '');
                        break;

                    case 'findLibrosConEditorial':
                        $result = $libroRepository->findLibrosConEditorial($param1 ?? '');
                        break;
                    //QB: Con QueryBuilder
                    case 'findLibrosConEditorialQB':
                        $result = $libroRepository->findLibrosConEditorialQB();
                        break;
                    case 'findLibrosConAutores':
                        $result = $libroRepository->findLibrosConAutores();
                        break;
                    case 'countLibros':
                        $result = $libroRepository->countLibros();
                        break;
                    case 'totalUnidadesVendidas':
                        $result = $libroRepository->totalUnidadesVendidas();
                        break;
                    case 'findLibrosPorIdOTituloConExpr':
                        $result = $libroRepository->findLibrosPorIdOTituloConExpr((int) $param1, $param2 ?? '');
                        break;
                    default:
                        $error = "Método '$method' no encontrado en LibroRepository";
                }
            } elseif ($entity === 'autor') {
                switch ($method) {
                    case 'findAutoresDesdeFecha':
                        $fecha = $param1 ? new \DateTime($param1) : new \DateTime('1951-01-01');
                        $result = $autorRepository->findAutoresDesdeFecha($fecha);
                        break;
                    case 'findAutoresSuperVentas':
                        $min = $param1 ? (int) $param1 : 4999999;
                        $result = $autorRepository->findAutoresSuperVentas($min);
                        break;
                    case 'findAutoresYCountLibros':
                        $result = $autorRepository->findAutoresYCountLibros();
                        break;
                    case 'findAutoresYCountLibrosGroupByQB':
                        $result = $autorRepository->findAutoresYCountLibrosGroupByQB();
                        break;
              case 'findAutoresYCountLibrosSubconsultaByQB':
                        $result = $autorRepository->findAutoresYCountLibrosSubconsultaByQB();
                        break;
                        
                    default:
                        $error = "Método '$method' no encontrado en AutorRepository";
                }
            } else {
                $error = "Entidad '$entity' desconocida. Usar 'libro' o 'autor'.";
            }
        } catch (\Throwable $e) {
            $error = $e->getMessage();
        }

        return $this->render('doctrine_consultas/index.html.twig', [
            'entity' => $entity,
            'method' => $method,
            'param1' => $param1,
            'param2' => $param2,
            'result' => $result,
            'error' => $error,
        ]);
    }
}
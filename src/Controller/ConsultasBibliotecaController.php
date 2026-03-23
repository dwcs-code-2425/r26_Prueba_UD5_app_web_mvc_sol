<?php

namespace App\Controller;

use App\Repository\LibroRepository;
use App\Repository\AutorRepository;
use App\Repository\EditorialRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ConsultasBibliotecaController extends AbstractController
{
    #[Route('/consultas/biblioteca', name: 'consultas_biblioteca')]
    #[IsGranted('ROLE_USER')]
    public function index(
        LibroRepository $libroRepository,
        AutorRepository $autorRepository,
        EditorialRepository $editorialRepository
    ): Response {

        // 1️ Todos los libros
        $todosLibros = $libroRepository->findAll();

        // 2️ Los 5 libros más vendidos
        $masVendidos = $libroRepository->findBy(
            [],
            ['unidadesVendidas' => 'DESC'],
            5
        );

        // 3️ Buscar autor por nombre exacto
        $autor = $autorRepository->findOneBy([
            'nombre' => 'Gabriel García Márquez' 
        ]);

        // 4️ Libros de una editorial concreta
        $editorial = $editorialRepository->findOneBy([
            'nombre' => 'Planeta' 
        ]);

        $librosEditorial = $editorial ? $editorial->getLibros() : [];

        return $this->render('consultas_biblioteca/index.html.twig', [
            'todosLibros' => $todosLibros,
            'masVendidos' => $masVendidos,
            'autor' => $autor,
            'editorial' => $editorial,
            'librosEditorial' => $librosEditorial,
        ]);
    }
}
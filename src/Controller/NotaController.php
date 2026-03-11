<?php

namespace App\Controller;

use App\Entity\Nota;
use App\Repository\NotaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class NotaController extends AbstractController
{


    #[Route('/nota/new', name: 'app_nota_create')]
    public function createNota(EntityManagerInterface $entityManager): Response
    {

        $nota = new Nota();
        $nota->setTitulo('Mi primera nota');
        $nota->setDescripcion('Esta es la descripción de mi primera nota.');
        $nota->setFechaModificacion(new \DateTime());

        $entityManager->persist($nota); // Prepara la nota para ser guardada
        $entityManager->flush(); // Guarda la nota en la base de datos

        return $this->render('nota/index.html.twig', [
            'nota' => $nota,
        ]);
    }

    #[Route('/nota', name: 'app_nota_index')]
    public function index(NotaRepository $notaRepository): Response
    {
        $notas =
            $notaRepository->findAll(); // Devuelve un array de todas las notas

        return $this->render('nota/index.html.twig', [
            'notas' => $notas,
        ]);
    }


    #[Route('/nota/new/form', name: 'app_nota_create_form')]
    public function createNotaFromTemplate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $nota = null;
        $error = null;

        if ($request->getMethod() === 'POST') {

            $titulo = $request->request->get('titulo', null);
            $titulo = trim($titulo);
            if (!empty($titulo)) {


                $descripcion = $request->request->get('descripcion', null);

                $nota = new Nota();
                $nota->setTitulo($titulo);
                $nota->setDescripcion($descripcion);
                $nota->setFechaModificacion(new \DateTime());

                $entityManager->persist($nota); // Prepara la nota para ser guardada
                $entityManager->flush(); // Guarda la nota en la base de datos
            } else {

                $error = "El título es obligatorio para crear una nota.";
            }
        } else {
            $nota = new Nota();
        }

        return $this->render('nota/new.html.twig', [
            'nota' => $nota,
            'error' => $error,
        ]);
    }



}

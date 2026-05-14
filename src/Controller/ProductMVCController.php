<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ProductMVCController extends AbstractController
{
    #[Route('/product/mvc', name: 'app_product_m_v_c')]
    public function index(): Response
    {
        return $this->render('product_mvc/index.html.twig', [
            'controller_name' => 'ProductMVCController',
        ]);
    }
}

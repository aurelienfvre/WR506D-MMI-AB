<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {
        return $this->render('product/index.html.twig', [
            'list_products' => 'List of products',
        ]);
    }
    public function listProducts(): Response
    {
        return $this->render('product/list.html.twig');
    }

    #[Route('/product/{id}', name: 'view_product', requirements: ['id' => '\d+'])]
    public function viewProduct(int $id): Response
    {
        return $this->render('product/view.html.twig', [
            'id' => $id,
        ]);
    }
}

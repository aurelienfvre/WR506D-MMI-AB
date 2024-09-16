<?php

namespace App\Controller;

use Cocur\Slugify\Slugify;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;


class DemoController extends AbstractController
{
    #[Route('/demo', name: 'app_demo')]
    public function index(): Response
    {
        $slugify = new Slugify();
        $slug = $slugify->slugify('Hello World');

        $date = new \DateTime();

        return $this->render('demo/index.html.twig', [
            'date' => $date,
        ]);
    }
}
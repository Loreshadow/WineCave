<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class CellarController extends AbstractController
{
    #[Route('/cellar', name: 'app_cellar')]
    public function index(): Response
    {
        return $this->render('cellar/index.html.twig', [
            'controller_name' => 'CellarController',
        ]);
    }
}

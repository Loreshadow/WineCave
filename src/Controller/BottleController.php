<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BottleController extends AbstractController
{
    #[Route('/bottle', name: 'app_bottle')]
    public function index(): Response
    {
        return $this->render('bottle/index.html.twig', [
            'controller_name' => 'BottleController',
        ]);
    }
}

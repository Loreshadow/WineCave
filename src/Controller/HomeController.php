<?php

namespace App\Controller;

use App\Repository\CaveRepository;
use App\Repository\CellarRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CaveRepository $cellarRepository): Response
    {
        // Récupérer les caves publiques pour les afficher sur la page d'accueil
        $publicCellars = $cellarRepository->findBy(['visibility' => 'public'], ['createdAt' => 'DESC'], 6);

        return $this->render('home/index.html.twig', [
            'publicCellars' => $publicCellars,
        ]);
    }
}
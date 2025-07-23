<?php

namespace App\Controller;

use App\Repository\CaveRepository;
use App\Repository\CellarRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(CaveRepository $cellarRepository, EntityManagerInterface $em): Response
    {
        // Récupérer les caves publiques pour les afficher sur la page d'accueil
        $publicCellars = $cellarRepository->findBy(['visibility' => 'public'], ['createdAt' => 'DESC'], 6);

        
        $popularWines = $em->createQuery(
            'SELECT b, COUNT(v.id) AS views
             FROM App\Entity\Bouteille b
             LEFT JOIN b.bouteilleViews v
             GROUP BY b.id
             ORDER BY views DESC'
        )
        ->setMaxResults(3)
        ->getResult();

    return $this->render('home/index.html.twig', [
        // ...autres variables...
        'publicCellars' => $publicCellars,
        'popularWines' => array_map(fn($row) => $row[0], $popularWines),
    ]);
    }
}
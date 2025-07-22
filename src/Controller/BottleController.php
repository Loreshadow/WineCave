<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Bouteille;
use App\Form\BouteilleType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/bouteille')]
class BottleController extends AbstractController
{
    #[Route('/ajouter', name: 'bouteille_new')]
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $bouteille = new Bouteille();
        $form = $this->createForm(BouteilleType::class, $bouteille);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bouteille->setUser($this->getUser());
            $bouteille->setCreatedAt(new \DateTime());
            $bouteille->setUpdatedAt(new \DateTime());
            $bouteille->setPublic('0');
            $em->persist($bouteille);
            $em->flush();

            $this->addFlash('success', 'Bouteille ajoutée avec succès !');
            return $this->redirectToRoute('cave_index');
        }

        return $this->render('bottle/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/vins-publics', name: 'public_wines')]
    public function publicWines(EntityManagerInterface $em): Response
    {
        $bouteilles = $em->getRepository(Bouteille::class)->findBy(['public' => 1]);

        return $this->render('bottle/public_list.html.twig', [
            'bouteilles' => $bouteilles,
        ]);
    }
#[Route('/vins-par-pays/{id}', name: 'public_by_country')]
    public function publicByCountry(Pays $pays, EntityManagerInterface $em): Response
    {
        $bouteilles = $em->getRepository(Bouteille::class)->findBy([
            'public' => 1,
            'pays' => $pays,
        ]);

        return $this->render('bottle/public_by_country.html.twig', [
            'bouteilles' => $bouteilles,
            'pays' => $pays,
        ]);
    }
    #[Route('/vins-par-pays', name: 'public_countries')]
public function publicCountries(EntityManagerInterface $em): Response
{
    $pays = $em->getRepository(Pays::class)->findAll();

    return $this->render('bottle/countries.html.twig', [
        'pays' => $pays,
    ]);
}
}
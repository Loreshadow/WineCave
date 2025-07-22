<?php

namespace App\Controller;

use App\Entity\Bouteille;
use App\Form\BouteilleType;
use App\Repository\BouteilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/cave')]
class CellarController extends AbstractController
{

    #[Route('/', name: 'cave_index')]
    public function index(BouteilleRepository $bouteilleRepository): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $wines = $bouteilleRepository->findBy(['user' => $user]);

        $redWines = count(array_filter($wines, function($wine) {
            $color = $wine->getColor();
            return $color && strtolower($color->getName()) === 'rouge';
        }));
        
        $whiteWines = count(array_filter($wines, function($wine) {
            $color = $wine->getColor();
            return $color && strtolower($color->getName()) === 'blanc';
        }));
        
        $roseWines = count(array_filter($wines, function($wine) {
            $color = $wine->getColor();
            return $color && strtolower($color->getName()) === 'rosé';
        }));

        return $this->render('cave/index.html.twig', [
            'wines' => $wines,
            'redWines' => $redWines,
            'whiteWines' => $whiteWines,
            'roseWines' => $roseWines,
        ]);
    }
    #[Route('/{id}/edit', name: 'bouteille_edit')]
    public function edit(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(BouteilleType::class, $bouteille);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $bouteille->setUpdatedAt(new \DateTime());
            $em->flush();

            $this->addFlash('success', 'Bouteille modifiée avec succès !');
            return $this->redirectToRoute('cave_index');
        }

        return $this->render('bottle/edit.html.twig', [
            'form' => $form->createView(),
            'bouteille' => $bouteille,
        ]);
    }
    #[Route('/{id}/delete', name: 'bouteille_delete', methods: ['POST'])]
    public function delete(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bouteille->getId(), $request->request->get('_token'))) {
            $em->remove($bouteille);
            $em->flush();
            $this->addFlash('success', 'Bouteille supprimée avec succès !');
        }
        return $this->redirectToRoute('cave_index');
    }

        #[Route('/{id}/public-request', name: 'bouteille_public_request', methods: ['POST'])]
        public function publicRequest(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
        {
            if ($this->isCsrfTokenValid('public'.$bouteille->getId(), $request->request->get('_token'))) {
                // Marquer la bouteille comme en attente de validation
                $bouteille->setPublic(2);
                $em->flush();
                $this->addFlash('success', 'Votre demande de publication a été envoyée à l\'administrateur.');
            }
            return $this->redirectToRoute('cave_index');
        }
}
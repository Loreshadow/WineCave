<?php

namespace App\Controller;

use App\Entity\Pays;
use App\Entity\Bouteille;
use App\Form\BouteilleType;
use App\Entity\BouteilleView;
use App\Entity\CaveBouteille;
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


            $user = $this->getUser();
            if (!($user instanceof \App\Entity\User)) {
                throw new \LogicException('L\'utilisateur courant n\'est pas valide.');
            }
            $cave = $user->getCave();
            $caveBouteille = new CaveBouteille();
            $caveBouteille->setCave($cave);
            $caveBouteille->setBouteille($bouteille);
            $em->persist($caveBouteille);
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
   
    #[Route('/{id}', name: 'bouteille_show', requirements: ['id' => '\d+'])]
        public function show(Bouteille $bouteille, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($user) {
            $alreadyViewed = $em->getRepository(BouteilleView::class)->findOneBy([
                'user' => $user,
                'bouteille' => $bouteille,
            ]);

            if (!$alreadyViewed) {
                $bouteille->setViews($bouteille->getViews() + 1);
                $em->persist($bouteille);

                $view = new BouteilleView();
                $view->setUser($user);
                $view->setBouteille($bouteille);
                $em->persist($view);

                $em->flush();
            }
        }

        return $this->render('bottle/show.html.twig', [
            'bouteille' => $bouteille,
        ]);
    }

    #[Route('/bouteille/{id}/add-to-cave', name: 'bouteille_add_to_cave', methods: ['POST'])]
    public function addToCave(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        /** @var \App\Entity\User $user */
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        // Vérifie que l'utilisateur a bien une cave
        if (!method_exists($user, 'getCave') || !$user->getCave()) {
            throw new \LogicException('L\'utilisateur courant ne possède pas de cave.');
        }
        
        $cave = $user->getCave();

        // Vérifie si la bouteille est déjà dans la cave
        $existing = $em->getRepository(CaveBouteille::class)->findOneBy([
            'cave' => $cave,
            'bouteille' => $bouteille,
        ]);
        if ($existing) {
            $this->addFlash('warning', 'Cette bouteille est déjà dans votre cave.');
            return $this->redirectToRoute('public_wines');
        }

        if ($this->isCsrfTokenValid('add_to_cave' . $bouteille->getId(), $request->request->get('_token'))) {
            $caveBouteille = new CaveBouteille();
            $caveBouteille->setCave($cave);
            $caveBouteille->setBouteille($bouteille);
            $em->persist($caveBouteille);
            $em->flush();

            $this->addFlash('success', 'Bouteille ajoutée à votre cave !');
        }

        return $this->redirectToRoute('public_wines');
    }
}
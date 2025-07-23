<?php

namespace App\Controller;

use App\Entity\Cave;
use App\Entity\User;
use App\Entity\Bouteille;
use App\Form\BouteilleType;
use App\Entity\CaveBouteille;
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
    public function index(BouteilleRepository $bouteilleRepository, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $user = $this->getUser();
        /** @var \App\Entity\User $user */
        if (!$user) {
            return $this->redirectToRoute('login');
        }

        $cave = $user->getCave();
        $caveBouteilles = $em->getRepository(CaveBouteille::class)->findBy(['cave' => $cave]);
        $wines = array_map(fn($cb) => $cb->getBouteille(), $caveBouteilles);

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
            'cave' => $cave,
        ]);
    }

    #[Route('/user/{id}/public', name: 'user_public_profile')]
    public function publicProfile(User $user, EntityManagerInterface $em): Response
    {
        // Vérifie la visibilité de la cave ou du profil
        $cave = $user->getCave();
        if (!$cave || $cave->getVisibility() != 1) {
            throw $this->createNotFoundException('Profil non public.');
        }

        // Récupère les bouteilles publiques de l'utilisateur via la table de liaison
        $caveBouteilles = $em->getRepository(CaveBouteille::class)->findBy(['cave' => $cave]);
        $bouteilles = array_filter(
            array_map(fn($cb) => $cb->getBouteille(), $caveBouteilles),
            fn($b) => $b->getPublic() == 1
        );

        return $this->render('profile_page/public_profile.html.twig', [
            'user' => $user,
            'cave' => $cave,
            'bouteilles' => $bouteilles,
        ]);
    }

    #[Route('/caves', name: 'all_caves')]
    public function allCaves(EntityManagerInterface $em): Response
    {
        $caves = $em->getRepository(\App\Entity\Cave::class)->findAll();
        $user = $this->getUser();

        return $this->render('cave/all_caves.html.twig', [
            'caves' => $caves,
            'user' => $user,
        ]);
    }


    #[Route('/cave/{id}', name: 'cave_show')]
    public function showCave(int $id, EntityManagerInterface $em): Response
    {
        $cave = $em->getRepository(\App\Entity\Cave::class)->find($id);
        if (!$cave) {
            throw $this->createNotFoundException('Cave non trouvée.');
        }

        // Récupère les bouteilles de la cave via la table de liaison
        $caveBouteilles = $em->getRepository(CaveBouteille::class)->findBy(['cave' => $cave]);

        return $this->render('cave/show.html.twig', [
            'cave' => $cave,
            'caveBouteilles' => $caveBouteilles,
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
        $user = $this->getUser();
        if (!$user) {
            throw $this->createAccessDeniedException();
        }

        /** @var \App\Entity\User $user */
        $userEntity = $user instanceof \App\Entity\User ? $user : null;
        if (!$userEntity || !method_exists($userEntity, 'getCave')) {
            throw $this->createAccessDeniedException('User does not have a cave.');
        }

        if ($this->isCsrfTokenValid('delete'.$bouteille->getId(), $request->request->get('_token'))) {
            $cave = $userEntity->getCave();
            switch ($bouteille->getPublic()) {
                case 1:
                    // Si la bouteille est publique, on retire juste le lien avec la cave
                    $caveBouteille = $em->getRepository(CaveBouteille::class)->findOneBy([
                        'cave' => $cave,
                        'bouteille' => $bouteille,
                    ]);
                    if ($caveBouteille) {
                        $em->remove($caveBouteille);
                        $em->flush();
                        $this->addFlash('success', 'Bouteille retirée de votre cave !');
                    }
                    break;
                default:
                    // Vérifier si la bouteille est présente uniquement dans la cave de l'utilisateur
                    $count = $em->getRepository(CaveBouteille::class)->count(['bouteille' => $bouteille]);
                    if ($count <= 1) {
                        // Supprimer les vues associées à la bouteille
                        $conn = $em->getConnection();
                        $conn->executeStatement('DELETE FROM bouteille_view WHERE bouteille_id = :id', [
                            'id' => $bouteille->getId(),
                        ]);
                        $conn->executeStatement('DELETE FROM cave_bouteille WHERE bouteille_id = :id', [
                            'id' => $bouteille->getId(),
                        ]);
                        $em->remove($bouteille);
                        $em->flush();
                        $this->addFlash('success', 'Bouteille supprimée totalement !');
                    } else {
                        // Sinon, juste retirer le lien avec la cave
                        $caveBouteille = $em->getRepository(CaveBouteille::class)->findOneBy([
                            'cave' => $cave,
                            'bouteille' => $bouteille,
                        ]);
                        if ($caveBouteille) {
                            $em->remove($caveBouteille);
                            $em->flush();
                            $this->addFlash('success', 'Bouteille retirée de votre cave !');
                        }
                    }
                    break;
            }
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
<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CaveBouteille;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ColorType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

final class ProfilePageController extends AbstractController
{
    #[Route('/profil', name: 'profile')]
    public function profile(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user || !($user instanceof \App\Entity\User)) {
            return $this->redirectToRoute('login');
        }
        $caveName = $user->getCave()?->getName();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, [
                'label' => 'Pseudo',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('caveName', TextType::class, [
                'label' => 'Nom de la cave',
                'attr' => ['class' => 'form-control'],
                'mapped' => false,
                'data' => $caveName,
                'required' => false,
            ])
            ->add('pseudoColor', ColorType::class, [
                'label' => 'Couleur du pseudo',
                'attr' => ['class' => 'form-control'],
                'required' => false,
            ])
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $newCaveName = $form->get('caveName')->getData();
            if ($user->getCave() && $newCaveName) {
                $user->getCave()->setName($newCaveName);
            }
            $em->flush();
            $this->addFlash('success', 'Profil mis à jour !');
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile_page/index.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/user/{id}/public', name: 'user_public_profile')]
    public function publicProfile(User $user, EntityManagerInterface $em): Response
    {
        // Vérifie la visibilité de la cave ET du profil utilisateur
        $cave = $user->getCave();
        if (!$cave || (int)$cave->getVisibility() !== 1 || (int)$user->getPrivacy() !== 1) {
            throw $this->createNotFoundException('Profil non public.');
        }
        
        // Récupère les bouteilles publiques
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

    #[Route('/profil/preferences', name: 'user_preferences', methods: ['POST'])]
    public function preferences(Request $request, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();
        if (!$user || !($user instanceof User) || !$user->getCave()) {
            return $this->redirectToRoute('login');
        }

        // Cave publique/privée
        $isCavePublic = $request->request->get('public_cave') ? 1 : 0;
        $user->getCave()->setVisibility($isCavePublic);

        // Profil public/privé
    
        $user->setPrivacy($request->request->get('public_profile') ? 1 : 0);

        $em->flush();
        $em->refresh($user->getCave());
        $em->refresh($user);

        $this->addFlash('success', 'Préférences mises à jour !');
        return $this->redirectToRoute('profile');
    }
}
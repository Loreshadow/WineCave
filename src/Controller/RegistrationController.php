<?php

namespace App\Controller;

use App\Entity\Cave;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Générer un username basé sur l'email
            $user->setUsername($user->getEmail());
            
            // Encoder le mot de passe
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            
            $cave = new Cave();
            $cave->setName('Cave de ' . $user->getEmail()); 
            $cave->setVisibility(1);
            $cave->setCreatedAt(new \DateTime());
            $cave->setUser($user);
            $user->setCave($cave);
            $entityManager->persist($cave);
            $entityManager->persist($user);
            $entityManager->flush();
            $user->setRoles(['ROLE_USER']);

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Votre compte a été créé avec succès !');

            return $this->redirectToRoute('login');
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }
}
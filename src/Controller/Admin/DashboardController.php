<?php

namespace App\Controller\Admin;

use App\Entity\Bouteille;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin_dashboard')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
    
        #[Route('/admin/public-requests', name: 'admin_public_requests')]
        public function publicRequests(EntityManagerInterface $em): Response
        {
            $bouteilles = $em->getRepository(Bouteille::class)->findBy(['public' => 2]);
            return $this->render('admin/public_request.html.twig', [
                'bouteilles' => $bouteilles,
            ]);
        }


        
    
    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Winecave');
    }

    public function configureMenuItems(): iterable
    {
        
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToRoute('Demandes de publication', 'fa fa-globe', 'admin_public_requests');
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-users', 'App\Entity\User');
        yield MenuItem::linkToCrud('Bouteilles', 'fa fa-wine-bottle', Bouteille::class);
        yield MenuItem::linkToRoute('Retour à l\'accueil', 'fa fa-arrow-left', 'app_home');
    }
    #[Route('/admin/bouteille/{id}/accept', name: 'admin_accept_public', methods: ['POST'])]
        public function acceptPublic(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
        {
            if ($this->isCsrfTokenValid('accept'.$bouteille->getId(), $request->request->get('_token'))) {
                $bouteille->setPublic(1);
                $em->flush();
                $this->addFlash('success', 'Bouteille rendue publique.');
            }
            return $this->redirectToRoute('admin_public_requests');
        }

        #[Route('/admin/bouteille/{id}/refuse', name: 'admin_refuse_public', methods: ['POST'])]
        public function refusePublic(Request $request, Bouteille $bouteille, EntityManagerInterface $em): Response
        {
            if ($this->isCsrfTokenValid('refuse'.$bouteille->getId(), $request->request->get('_token'))) {
                $bouteille->setPublic(0);
                $em->flush();
                $this->addFlash('danger', 'Demande refusée.');
            }
            return $this->redirectToRoute('admin_public_requests');
        }
}

<?php

namespace App\Controller\Admin;

use App\Entity\Adoptant;
use App\Entity\AdoptionOffer;
use App\Entity\Annonce;
use App\Entity\Annonceur;
use App\Entity\Breed;
use App\Entity\Dog;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'dashboard_admin')]
    public function index(): Response
    {
        // return parent::index();

        // Option 1. You can make your dashboard redirect to some common page of your backend
        //
        // $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);
        // return $this->redirect($adminUrlGenerator->setController(OneOfYourCrudController::class)->generateUrl());

        // Option 2. You can make your dashboard redirect to different pages depending on the user
        //
        // if ('jane' === $this->getUser()->getUsername()) {
        //     return $this->redirect('...');
        // }

        // Option 3. You can render some custom template to display a proper dashboard with widgets, etc.
        // (tip: it's easier if your template extends from @EasyAdmin/page/content.html.twig)
        //
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('MyDoggyProject');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('User', 'fas fa-list', User::class);
        yield MenuItem::linkToCrud('Annonceur', 'fas fa-list', Annonceur::class);
        yield MenuItem::linkToCrud('Adoptant', 'fas fa-list', Adoptant::class);
        yield MenuItem::linkToCrud('Annonce', 'fas fa-list', Annonce::class);
        yield MenuItem::linkToCrud('AdoptionOffer', 'fas fa-list', AdoptionOffer::class);
        yield MenuItem::linkToCrud('Dog', 'fas fa-list', Dog::class);
        yield MenuItem::linkToCrud('Breed', 'fas fa-list', Breed::class);
    }
}

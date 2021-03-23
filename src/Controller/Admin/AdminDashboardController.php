<?php

namespace App\Controller\Admin;

use App\Entity\Artifact;
use App\Entity\Build;
use App\Entity\Character;
use App\Entity\CommunityBuild;
use App\Entity\OfficialBuild;
use App\Entity\User;
use App\Entity\Weapon;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminDashboardController extends AbstractDashboardController
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index(): Response
    {
        $routeBuilder = $this->get(AdminUrlGenerator::class);

        return $this->redirect($routeBuilder->setController(CharacterCrudController::class)->generateUrl());
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Genshin Dashboard');
    }

    public function configureMenuItems(): iterable
    {
        return [
            MenuItem::linktoDashboard('Dashboard', 'fa fa-home'),

            MenuItem::section('Wiki Content'),
            MenuItem::linkToCrud('Characters', 'fas fa-user', Character::class),
            MenuItem::linkToCrud('Weapons','fas fa-gavel', Weapon::class),
            MenuItem::linkToCrud('Artifacts', 'fas fa-feather-alt', Artifact::class),

            MenuItem::section('Community'),
            MenuItem::linkToCrud('Users', 'fas fa-users', User::class),

            MenuItem::section('Build'),
            MenuItem::linkToCrud('Empty Builds', 'fas fa-scroll', Build::class),
            MenuItem::linkToCrud('Official Builds', 'fas fa-scroll', OfficialBuild::class),
            MenuItem::linkToCrud('Community Builds', 'fas fa-scroll', CommunityBuild::class),

            //MenuItem::linkToLogout('Logout', 'fa fa-exit'),
        ];
    }
}

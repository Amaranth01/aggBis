<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {

    }
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        $url = $this->adminUrlGenerator->setController(ArticleCrudController::class)->generateUrl();

        return $this->redirect($url);

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('AggSnd');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Articles');
        yield MenuItem::subMenu('Articles management', 'fa-regular fa-newspaper')->setSubItems([
            MenuItem::linkToCrud('Articles add', 'fas fa-plus', Article::class)->setAction(Crud::PAGE_NEW),
            MenuItem::linkToCrud('Articles list', 'fa-solid fa-list', Article::class),
        ]);

        yield MenuItem::section('Comments');
        yield MenuItem::subMenu('Comments management', 'fa-solid fa-users')->setSubItems([
            MenuItem::linkToCrud('Comments list', 'fas fa-eyes', Comment::class),
        ]);

        yield MenuItem::section('Users');
        yield MenuItem::subMenu('Users management', 'fa-regular fa-comment')->setSubItems([
            MenuItem::linkToCrud('Users list', 'fas fa-eyes', User::class),
        ]);
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}

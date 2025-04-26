<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Entity\Order;
use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_ADMIN')]
#[AdminDashboard(routePath: '/admin', routeName: 'admin')]
class DashboardController extends AbstractDashboardController
{
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');

    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('<img src="/images/logo.png" alt="Ferme De La Rougeraie" height="100">');    }

    public function configureMenuItems(): iterable
    {
//        yield MenuItem::linkToDashboard('Dashboard', 'fa-solid fa-chart-simple');
        yield MenuItem::linkToCrud('Produits', 'fa-solid fa-carrot', Product::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-box', Order::class);
        yield MenuItem::linkToRoute('Tableau commandes', 'fa-solid fa-table-list', 'admin_product_client_tab');
        yield MenuItem::linkToCrud('Messages', 'fas fa-comment', Message::class);
        yield MenuItem::linkToRoute('Utilisateurs', 'fa-solid fa-users', 'admin_user_list_tab');
    }
}
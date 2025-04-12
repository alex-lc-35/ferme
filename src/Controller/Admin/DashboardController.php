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
            ->setTitle('Ferme De La Rougeraie ');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Produits', 'fas fa-apple-alt', Product::class);
        yield MenuItem::linkToCrud('Commandes', 'fas fa-box', Order::class);
        yield MenuItem::linkToRoute('Tableau commandes', 'fa fa-table', 'admin_product_client_tab');
        yield MenuItem::linkToCrud('Messages', 'fas fa-comment', Message::class);
    }

}
<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductClientTabController extends AbstractController
{
    #[Route('/admin/product-client-tab', name: 'admin_product_client_tab')]
    public function index(
        ProductRepository $productRepository,
        UserRepository $userRepository
    ): Response {
        // Récupérer les produits dont la somme des quantités commandées est > 0
           $products = $productRepository->createQueryBuilder('p')
               ->join('p.productOrders', 'po')
               ->join('po.orderId', 'o')
               ->andWhere('o.isDeleted = false')
               ->groupBy('p.id')
               ->having('SUM(po.quantity) > 0')
               ->getQuery()
               ->getResult();

        // Récupérer les utilisateurs ayant passé au moins une commande non supprimée
        $users = $userRepository->createQueryBuilder('u')
            ->join('u.orders', 'o')
            ->andWhere('o.isDeleted = false')
            ->getQuery()
            ->getResult();

        // Construire le tableau des quantités
        $quantitiesTab = [];

        foreach ($users as $user) {
            $userRow = [];
            foreach ($products as $product) {
                $totalQty = 0;
                foreach ($user->getOrders() as $order) {
                    if ($order->isDeleted()) {
                        continue;
                    }
                    foreach ($order->getProductOrders() as $productOrder) {
                        if ($productOrder->getProduct()->getId() === $product->getId()) {
                            $totalQty += $productOrder->getQuantity();
                        }
                    }
                }
                $userRow[$product->getId()] = $totalQty;
            }
            $quantitiesTab[$user->getId()] = $userRow;
        }

        return $this->render('admin/product_client_tab.html.twig', [
            'products'      => $products,
            'users'         => $users,
            'quantitiesTab' => $quantitiesTab,
        ]);
    }
}

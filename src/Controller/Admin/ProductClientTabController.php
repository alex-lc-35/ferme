<?php

namespace App\Controller\Admin;

use App\Repository\ProductRepository;
use App\Repository\UserRepository;
use App\Service\ProductClientTabService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductClientTabController extends AbstractController
{
    #[Route('/admin/product-client-tab', name: 'admin_product_client_tab')]
    public function index(
        ProductRepository $productRepository,
        UserRepository $userRepository,
        ProductClientTabService $productClientTabService
    ): Response {
        $products = $productRepository->findProductsWithOrderedQuantities();

        $users = $userRepository->findUsersWithNonDeletedOrders();

        $quantitiesTab = $productClientTabService->calculateQuantities($users, $products);

        return $this->render('admin/product_client_tab.html.twig', [
            'products'      => $products,
            'users'         => $users,
            'quantitiesTab' => $quantitiesTab,
        ]);
    }
}

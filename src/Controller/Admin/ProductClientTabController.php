<?php

namespace App\Controller\Admin;
use App\Service\ProductClientTabService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductClientTabController extends AbstractController
{
    #[Route('/admin/product-client-tab', name: 'admin_product_client_tab')]
    public function index(
        ProductClientTabService $productClientTabService
    ): Response {
        [$products, $users, $quantitiesTab] = $productClientTabService->getProductClientQuantities();

        return $this->render('admin/product_client_tab.html.twig', [
            'products'      => $products,
            'users'         => $users,
            'quantitiesTab' => $quantitiesTab,
        ]);
    }

}

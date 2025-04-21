<?php

namespace App\Controller\Store;

use App\Service\Store\OrderStoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/orders', name: 'store_orders_')]
class OrderStoreController extends AbstractController
{
    #[Route('', name: 'list', methods: ['GET'])]
    public function list(OrderStoreService $orderStoreService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $orders = $orderStoreService->getOrdersForUser($user);

        return $this->json($orders);
    }
}

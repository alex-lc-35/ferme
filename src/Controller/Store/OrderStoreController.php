<?php

namespace App\Controller\Store;

use App\Dto\Order\Create\CartItemDto;
use App\Dto\Order\Create\OrderCreateDto;
use App\Service\Store\OrderStoreService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
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

        $orderDtos = $orderStoreService->getOrdersForUser($user);

        return $this->json($orderDtos);
    }

    #[Route('/create', name: 'create', methods: ['POST'])]
    public function create(Request $request, OrderStoreService $orderStoreService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $data = json_decode($request->getContent(), true);

        if (empty($data['items']) || empty($data['pickup'])) {
            return $this->json(['error' => 'Champs manquants.'], 400);
        }

        try {
            $dto = new OrderCreateDto(
                items: array_map(
                    fn(array $item) => new CartItemDto($item['productId'], $item['quantity']),
                    $data['items']
                ),
                pickup: $data['pickup']
            );

            $order = $orderStoreService->createOrderFromCart($dto, $user);

            return $this->json([
                'success' => true,
                'orderId' => $order->getId()
            ], 201);

        } catch (\DomainException $e) {
            return $this->json(['error' => $e->getMessage()], 400);

        } catch (\ValueError $e) {
            return $this->json(['error' => 'Valeur de retrait invalide.'], 400);
        }
    }

}

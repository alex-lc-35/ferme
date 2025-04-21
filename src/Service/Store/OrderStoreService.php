<?php

namespace App\Service\Store;

use App\Dto\OrderWithItemsDto;
use App\Entity\User;
use App\Mapper\OrderMapper;
use App\Repository\Store\OrderStoreRepository;

class OrderStoreService
{
    public function __construct(
        private OrderStoreRepository $orderRepository
    ) {}

    /**
     * @return OrderWithItemsDto[]
     */
    public function getOrdersForUser(User $user): array
    {
        $orders = $this->orderRepository->findOrdersByUser($user);

        return array_map(
            fn($order) => OrderMapper::toDto($order),
            $orders
        );
    }
}

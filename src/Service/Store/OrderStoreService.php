<?php

namespace App\Service\Store;

use App\Dto\OrderWithItemsDto;
use App\Entity\User;
use App\Mapper\OrderMapper;

class OrderStoreService
{
    /**
     * Get orders by user.
     *
     * @return OrderWithItemsDto[]
     */
    public function getOrdersForUser(User $user): array
    {
        $orders = $user->getOrders()->filter(fn($o) => !$o->isDeleted());

        return array_map(
            fn($order) => OrderMapper::toDto($order),
            $orders->toArray()
        );
    }
}
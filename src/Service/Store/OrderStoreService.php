<?php

namespace App\Service\Store;

use App\Dto\OrderWithItemsDto;
use App\Entity\User;

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

        return array_map(function ($order) {
            $items = [];

            foreach ($order->getProductOrders() as $po) {
                $product = $po->getProduct();

                $items[] = [
                    'productId' => $product->getId(),
                    'productName' => $product->getName(),
                    'unitPrice' => $po->getUnitPrice() / 100,
                    'quantity' => $po->getQuantity(),
                ];
            }

            return new OrderWithItemsDto(
                id: $order->getId(),
                total: $order->getTotal(),
                pickup: $order->getPickup()?->value,
                createdAt: $order->getCreatedAt(),
                done: $order->isDone(),
                items: $items
            );
        }, $orders->toArray());
    }
}

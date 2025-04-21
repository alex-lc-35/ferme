<?php

namespace App\Mapper;

use App\Dto\OrderWithItemsDto;
use App\Entity\Order;

class OrderMapper
{
    public static function toDto(Order $order): OrderWithItemsDto
    {
        $items = [];

        foreach ($order->getProductOrders() as $po) {
            $product = $po->getProduct();

            if (!$product) {
                continue;
            }

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
    }
}

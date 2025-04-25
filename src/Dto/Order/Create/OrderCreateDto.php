<?php

namespace App\Dto\Order\Create;
/**
 * Data required to create a new order.
 *
 * @param CartItemDto[] $items List of cart items for the order
 * @param string $pickup Pickup day
 */
class OrderCreateDto
{
    public function __construct(
        public array $items,
        public string $pickup
    ) {}
}

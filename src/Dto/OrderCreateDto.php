<?php

namespace App\Dto;

class OrderCreateDto
{
    /**
     * @param CartItemDto[] $items
     */
    public function __construct(
        public array $items,
        public string $pickup
    ) {}
}

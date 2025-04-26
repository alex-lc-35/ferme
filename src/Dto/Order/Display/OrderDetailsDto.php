<?php

namespace App\Dto\Order\Display;

readonly class OrderDetailsDto
{
    /**
     * detailed order information for display to the client
     *
     * @param OrderItemDto[] $items
     */
    public function __construct(
        public int $id,
        public float  $total,
        public string $pickup,
        public \DateTimeImmutable $createdAt,
        public bool $done,
        public array $items,
    ) {}
}

<?php

namespace App\Dto;

readonly class OrderWithItemsDto
{
    public function __construct(
        public int $id,
        public int $total,
        public string $pickup,
        public \DateTimeImmutable $createdAt,
        public bool $done,
        public array $items
    ) {}
}
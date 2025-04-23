<?php
namespace App\Dto;

readonly class ProductOrderDto
{
    public function __construct(
        public string $productName,
        public int $quantity,
        public int $unitPrice
    ) {}
}

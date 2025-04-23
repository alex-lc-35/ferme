<?php
namespace App\Dto;

readonly class CartItemDto
{
public function __construct(
public int $productId,
public int $quantity
) {}
}

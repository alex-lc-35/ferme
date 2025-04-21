<?php

namespace App\Dto;

class ProductDto
{
public function __construct(
public int $id,
public string $name,
public float $price,
public string $unit,
public string $image,
public ?int $stock,
public bool $limited,
public bool $discount,
public ?string $discountText,
) {}
}

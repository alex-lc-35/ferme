<?php

namespace App\Dto\Product;

readonly class ProductAdminDto
{
    public function __construct(
        public int     $id,
        public string  $name,
        public string  $unit,
        public bool    $isDisplayed,
        public bool    $isDeleted,
    ) {}
}
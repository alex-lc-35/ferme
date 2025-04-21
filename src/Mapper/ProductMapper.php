<?php

namespace App\Mapper;

use App\Dto\ProductDto;
use App\Entity\Product;

class ProductMapper
{
    public static function toDto(Product $product): ProductDto
    {
        return new ProductDto(
            id: $product->getId(),
            name: $product->getName(),
            price: $product->getPriceInEuros(),
            unit: $product->getUnit()?->value ?? 'unknown',
            image: '/uploads/images/' . $product->getImage(),
            stock: $product->hasStock() ? $product->getStock() : null,
            limited: $product->isLimited(),
            discount: $product->isDiscount(),
            discountText: $product->getDiscountText(),
        );
    }
}

<?php

namespace App\Mapper;

use App\Dto\Product\ProductAdminDto;
use App\Entity\Product;
use App\Utils\Translator\UnitTranslator;

class ProductAdminMapper
{
    public static function toDto(Product $product, UnitTranslator $translator): ProductAdminDto
    {
        return new ProductAdminDto(
            id: $product->getId(),
            name: $product->getName(),
            unit: $translator->translate($product->getUnit()),
            isDisplayed: $product->isDisplayed(),
            isDeleted: $product->isDeleted(),
        );
    }
}

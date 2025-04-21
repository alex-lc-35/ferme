<?php

namespace App\Service\Store;

use App\Dto\ProductDto;
use App\Repository\Store\ProductStoreRepository;

class ProductStoreService
{
    public function __construct(
        private ProductStoreRepository $productRepository
    ) {}

    /**
     * Get displayed products.
     *
     * @return ProductDto[]
     */
    public function getAvailableProductsForFront(): array
    {
        $products = $this->productRepository->findDisplayedAvailableProducts();

        return array_map(fn($product) => new ProductDto(
            id: $product->getId(),
            name: $product->getName(),
            price: $product->getPriceInEuros(),
            unit: $product->getUnit()?->value ?? 'unknown',
            image: '/uploads/images/' . $product->getImage(),
            stock: $product->hasStock() ? $product->getStock() : null,
            limited: $product->isLimited(),
            discount: $product->isDiscount(),
            discountText: $product->getDiscountText(),
        ), $products);
    }
}

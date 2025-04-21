<?php

namespace App\Service\Store;

use App\Dto\ProductDto;
use App\Entity\Product;
use App\Repository\Store\ProductStoreRepository;
use App\Mapper\ProductMapper;

class ProductStoreService
{
    public function __construct(
        private ProductStoreRepository $productRepository
    )
    {}
    /**
     * Get displayed products.
     *
     * @return ProductDto[]
     */
    public function getAvailableProductsForFront(): array
    {
        $products = $this->productRepository->findDisplayedAvailableProducts();

        return array_map(
            fn(Product $product) => ProductMapper::toDto($product),
            $products
        );

    }
}
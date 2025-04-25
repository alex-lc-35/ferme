<?php

namespace App\Service\Store;

use App\Dto\Product\ProductDto;
use App\Entity\Product;
use App\Mapper\ProductMapper;
use App\Repository\Store\ProductStoreRepository;

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
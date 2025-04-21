<?php

namespace App\Service\Admin;

use App\Repository\Admin\ProductRepository;

class ProductService
{
    public function __construct(
        private ProductRepository $productRepository,
    ) {}
    public function markProductsAsDeletedByIds(array $ids): array
    {
        $nonDeletableNames = $this->productRepository->findNonDeletableProductNames($ids);

        $deletableIds = $this->productRepository->findDeletableProductIds($ids);

        if (!empty($deletableIds)) {
            $this->productRepository->softDeleteProductsByIds($deletableIds);
        }

        return $nonDeletableNames;
    }
}
<?php

namespace App\Service\Admin;

use App\Repository\Admin\ProductOrderRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\UserRepository;

class ProductClientTabService
{
    public function __construct(
        private ProductOrderRepository $productOrderRepository,
        private ProductRepository $productRepository,
        private UserRepository $userRepository
    ) {}

    public function getProductClientQuantities(): array
    {
        $products = $this->productRepository->findProductsWithOrderedQuantities();

        $rawQuantities = $this->productOrderRepository->getUserProductQuantities();

        $quantitiesTab = $this->buildQuantitiesTab($rawQuantities);

        $userIds = array_keys($quantitiesTab);
        $users = $this->userRepository->findUsersByIds($userIds);

        return [$products, $users, $quantitiesTab];
    }

    private function buildQuantitiesTab(array $rawData): array
    {
        $tab = [];
        foreach ($rawData as $row) {
            $tab[$row['userId']][$row['productId']] = (int) $row['totalQuantity'];
        }
        return $tab;
    }
}

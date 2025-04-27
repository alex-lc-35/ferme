<?php
// src/Service/Admin/ProductClientTabService.php

namespace App\Service\Admin;

use App\Entity\Product;
use App\Entity\User;
use App\Enum\PickupDay;
use App\Repository\Admin\ProductOrderRepository;
use App\Repository\Admin\ProductRepository;
use App\Repository\Admin\UserRepository;

class ProductClientTabService
{
    public function __construct(
        private ProductOrderRepository $productOrderRepository,
        private ProductRepository      $productRepository,
        private UserRepository         $userRepository
    ) {}

    /**
     * @return array{0: array<Product>, 1: array<User>, 2: array<int,array<int,int>>}
     */
    public function getProductClientQuantities(PickupDay $pickupDay): array
    {
        // 1) Récupérer les IDs de produits qui ont au moins une commande ce jour
        $productIds = $this->productOrderRepository
            ->getProductIdsByPickupDay($pickupDay);

        // 2) Charger uniquement ces produits (ou tableau vide si aucun)
        $products = $productIds
            ? $this->productRepository->findBy(['id' => $productIds])
            : [];

        // 3) Récupérer les quantités brutes
        $rawQuantities = $this->productOrderRepository
            ->getUserProductQuantitiesByPickupDay($pickupDay);

        // 4) Construire la grille [userId][productId] => qty
        $quantitiesTab = $this->buildQuantitiesTab($rawQuantities);

        // 5) Charger les utilisateurs concernés
        $userIds = array_keys($quantitiesTab);
        $users   = $userIds
            ? $this->userRepository->findUsersByIds($userIds)
            : [];

        return [$products, $users, $quantitiesTab];
    }

    private function buildQuantitiesTab(array $rawData): array
    {
        $tab = [];
        foreach ($rawData as $row) {
            $userId    = (int) $row['userId'];
            $productId = (int) $row['productId'];
            $qty       = (int) $row['totalQuantity'];
            $tab[$userId][$productId] = $qty;
        }
        return $tab;
    }
}

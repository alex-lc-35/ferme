<?php

namespace App\Service\Store;

use App\Entity\Product;
use App\Repository\Admin\ProductRepository;

class StockStoreService
{
    public function __construct(
        private ProductRepository $productRepository
    ) {}

    /**
     * Check stock for one product.
     *
     * @throws \DomainException
     */
    public function checkAndDecreaseStock(int $productId, int $quantity): Product
    {
        $product = $this->productRepository->find($productId);

        if (!$product || !$product->isDisplayed() || $product->isDeleted()) {
            throw new \DomainException("Produit non disponible");
        }

        if ($product->hasStock() && !$product->canDecrementStock($quantity)) {
            throw new \DomainException(
                "Stock insuffisant pour le produit : {$product->getName()}. Quantité restante : {$product->getStock()}."
            );
        }

        if ($product->hasStock()) {
            $product->decrementStock($quantity);
        }

        return $product;
    }
}

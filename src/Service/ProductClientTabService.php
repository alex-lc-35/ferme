<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Product;

class ProductClientTabService
{
    /**
     * Calcule le tableau associatif des quantités commandées
     * pour chaque utilisateur et chaque produit.
     *
     * @param User[]    $users    Liste des utilisateurs
     * @param Product[] $products Liste des produits
     *
     * @return array Renvoie un tableau sous la forme :
     *               [
     *                  userId => [
     *                      productId => totalQuantity,
     *                  ],
     *               ]
     */
    public function calculateQuantities(array $users, array $products): array
    {
        $quantitiesTab = [];

        foreach ($users as $user) {
            $userRow = [];

            foreach ($products as $product) {
                $totalQty = 0;

                foreach ($user->getOrders() as $order) {
                    if ($order->isDeleted()) {
                        continue;
                    }

                    foreach ($order->getProductOrders() as $productOrder) {
                        if ($productOrder->getProduct()->getId() === $product->getId()) {
                            $totalQty += $productOrder->getQuantity();
                        }
                    }
                }

                // Affecte la quantité totale pour ce produit à l'utilisateur
                $userRow[$product->getId()] = $totalQty;
            }

            // Place le tableau des quantités pour l'utilisateur identifié par son id
            $quantitiesTab[$user->getId()] = $userRow;
        }

        return $quantitiesTab;
    }
}

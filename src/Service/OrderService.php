<?php

namespace App\Service;

use App\Repository\OrderRepository;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    public function markOrdersAsDeletedByIds(array $ids): array
    {
        // 1. On récupère les commandes supprimables directement
        $deletableIds = $this->orderRepository->findDoneOrderIds($ids);

        // 2. Soft delete en DQL
        if (!empty($deletableIds)) {
            $this->orderRepository->softDeleteDoneOrdersByIds($deletableIds);
        }

        // 3. Les non-supprimables = ceux qu'on a sélectionnés - ceux qu'on a pu supprimer
        $nonDeletableIds = array_diff($ids, $deletableIds);

        return $nonDeletableIds;
    }
}

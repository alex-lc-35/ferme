<?php

namespace App\Service\Admin;

use App\Repository\Admin\OrderRepository;

class OrderService
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {}

    /**
     * Mark orders as deleted by their IDs.
     *
     * @param int[] $ids
     * @return int[] IDs of orders that could not be marked as deleted
     */
    public function markOrdersAsDeletedByIds(array $ids): array
    {
        $deletableIds = $this->orderRepository->findDoneOrderIds($ids);

        if (!empty($deletableIds)) {
            $this->orderRepository->softDeleteDoneOrdersByIds($deletableIds);
        }
        $nonDeletableIds = array_diff($ids, $deletableIds);

        return $nonDeletableIds;
    }
}

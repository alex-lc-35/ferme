<?php
namespace App\Service\Store;

use App\Dto\Order\Create\OrderCreateDto;
use App\Dto\Order\Display\OrderDetailsDto;
use App\Entity\Order;
use App\Entity\User;
use App\Mapper\OrderMapper;
use App\Repository\Store\OrderStoreRepository;

class OrderStoreService
{

    public function __construct(
        private OrderStoreRepository   $orderStoreRepository,
        private StockStoreService      $stockService,
    ) {}


    /**
     * @param User $user
     * @return OrderDetailsDto[]
     */
    public function getOrdersForUser(User $user): array
    {
        $orders = $this->orderStoreRepository->findOrdersByUser($user);
        $dtos = [];
        foreach ($orders as $order) {
            $dtos[] = OrderMapper::toDto($order);
        }
        return $dtos;
    }

    public function createOrderFromCart(OrderCreateDto $orderCreateDto, User $user): Order
    {
        $productData = [];

        foreach ($orderCreateDto->items as $item) {
            $product = $this->stockService->checkAndDecreaseStock($item->productId, $item->quantity);
            $productData[] = [
                'product' => $product,
                'quantity' => $item->quantity,
            ];
        }
        $order = OrderMapper::fromDto($orderCreateDto, $user, $productData);

        $this->orderStoreRepository->save($order);
        return $order;
    }
}

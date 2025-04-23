<?php
namespace App\Service\Store;

use App\Dto\OrderCreateDto;
use App\Dto\OrderWithItemsDto;
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
     * @return OrderWithItemsDto[]
     */
    public function getOrdersForUser(User $user): array
    {
        $orders = $this->orderStoreRepository->findOrdersByUser($user);

        return array_map([OrderMapper::class, 'toDto'], $orders);
    }

    public function createOrderFromCart(OrderCreateDto $dto, User $user): Order
    {
        $productData = [];

        foreach ($dto->items as $item) {
            $product = $this->stockService->checkAndDecreaseStock($item->productId, $item->quantity);
            $productData[] = [
                'product' => $product,
                'quantity' => $item->quantity,
            ];
        }
        $order = OrderMapper::fromDto($dto, $user, $productData);

        $this->orderStoreRepository->save($order);
        return $order;
    }

}

<?php

namespace App\Repository\Store;

use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }

    /**
     * @return Order[]
     */
    public function findOrdersByUser(User $user): array
    {
        return $this->createQueryBuilder('o')
            ->where('o.user = :user')
            ->andWhere('o.isDeleted = false')
            ->setParameter('user', $user)
            ->orderBy('o.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}

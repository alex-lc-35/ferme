<?php

namespace App\Repository\Admin;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Order>
 */
class OrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Order::class);
    }
    public function findDoneOrderIds(array $ids): array
    {
        $results = $this->createQueryBuilder('o')
            ->select('o.id')
            ->where('o.id IN (:ids)')
            ->andWhere('o.done = true')
            ->setParameter('ids', $ids)
            ->getQuery()
            ->getScalarResult();

        return array_column($results, 'id');
    }

    public function softDeleteDoneOrdersByIds(array $ids): int
    {
        return $this->createQueryBuilder('o')
            ->update()
            ->set('o.isDeleted', ':true')
            ->where('o.id IN (:ids)')
            ->andWhere('o.done = true')
            ->setParameter('ids', $ids)
            ->setParameter('true', true)
            ->getQuery()
            ->execute();
    }


}

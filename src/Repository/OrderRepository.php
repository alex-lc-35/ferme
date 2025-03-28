<?php

namespace App\Repository;

use App\Entity\Order;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
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


    public function createNonDeletedQueryBuilder(string $alias = 'o'): QueryBuilder
    {
        return $this->createQueryBuilder($alias)
            ->where("$alias.isDeleted = :val")
            ->setParameter('val', false);
    }
    public function flush(): void
    {
        $this->getEntityManager()->flush();    }

}

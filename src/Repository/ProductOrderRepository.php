<?php

namespace App\Repository;

use App\Entity\ProductOrder;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProductOrder>
 */
class ProductOrderRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProductOrder::class);
    }
    public function getUserProductQuantities(): array
    {
        return $this->createQueryBuilder('po')
            ->select('IDENTITY(o.user) AS userId', 'IDENTITY(po.product) AS productId', 'SUM(po.quantity) AS totalQuantity')
            ->join('po.orderId', 'o')
            ->where('o.isDeleted = false')
            ->groupBy('userId', 'productId')
            ->getQuery()
            ->getArrayResult();
    }

}

<?php

namespace App\Repository\Admin;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Product>
 */
class ProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }



    /**
     * Get product with orders > 0.
     *
     * @return Product[]
     */
    public function findProductsWithOrderedQuantities(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.productOrders', 'po')
            ->join('po.order', 'o')
            ->andWhere('o.isDeleted = false')
            ->groupBy('p.id')
            ->having('SUM(po.quantity) > 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int[] Get product IDs that can be deleted (not linked to orders).
     */
    public function findDeletableProductIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->leftJoin('p.productOrders', 'po')
            ->leftJoin('po.order', 'o')
            ->where('p.id IN (:ids)')
            ->andWhere('o.isDeleted = true OR o.id IS NULL')
            ->setParameter('ids', $ids)
            ->groupBy('p.id');

        return array_column($qb->getQuery()->getScalarResult(), 'id');
    }

    /**
     * @return string[] Get product names that can not be deleted ( linked to orders)
     */
    public function findNonDeletableProductNames(array $ids): array
    {
        return array_column(
            $this->createQueryBuilder('p')
                ->select('DISTINCT p.name')
                ->join('p.productOrders', 'po')
                ->join('po.order', 'o')
                ->where('p.id IN (:ids)')
                ->andWhere('o.isDeleted = false')
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getScalarResult(),
            'name'
        );
    }


    /**
     * Soft delete products by IDs.
     *
     * @param int[] $ids
     * @return int Number of affected rows
     */

    public function softDeleteProductsByIds(array $ids): int
    {
        return $this->createQueryBuilder('p')
            ->update()
            ->set('p.isDeleted', ':true')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->setParameter('true', true)
            ->getQuery()
            ->execute();
    }

}

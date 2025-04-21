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
     * @return Product[] returns an array of Product
     */
    public function findAllProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.isDeleted = false')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Récupère les produits ayant une quantité totale commandée supérieure à 0.
     *
     * @return Product[] Renvoie un tableau d'entités Product
     */
    public function findProductsWithOrderedQuantities(): array
    {
        return $this->createQueryBuilder('p')
            ->join('p.productOrders', 'po')
            ->join('po.orderId', 'o')
            ->andWhere('o.isDeleted = false')
            ->groupBy('p.id')
            ->having('SUM(po.quantity) > 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return int[] IDs des produits qui n'ont pas de commandes non supprimées
     */
    public function findDeletableProductIds(array $ids): array
    {
        $qb = $this->createQueryBuilder('p')
            ->select('p.id')
            ->leftJoin('p.productOrders', 'po')
            ->leftJoin('po.orderId', 'o')
            ->where('p.id IN (:ids)')
            ->andWhere('o.isDeleted = true OR o.id IS NULL')
            ->setParameter('ids', $ids)
            ->groupBy('p.id');

        return array_column($qb->getQuery()->getScalarResult(), 'id');
    }

    /**
     * @return string[] Noms des produits liés à des commandes actives (non supprimées)
     */
    public function findNonDeletableProductNames(array $ids): array
    {
        return array_column(
            $this->createQueryBuilder('p')
                ->select('DISTINCT p.name')
                ->join('p.productOrders', 'po')
                ->join('po.orderId', 'o')
                ->where('p.id IN (:ids)')
                ->andWhere('o.isDeleted = false')
                ->setParameter('ids', $ids)
                ->getQuery()
                ->getScalarResult(),
            'name'
        );
    }


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

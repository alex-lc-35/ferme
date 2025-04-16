<?php

namespace App\Repository;

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

}

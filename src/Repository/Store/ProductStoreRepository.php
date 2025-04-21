<?php

namespace App\Repository\Store;

use App\Entity\Product;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ProductStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Product::class);
    }

    /**
     * Get displayed products.
     *
     * @return Product[]
     */
    public function findDisplayedAvailableProducts(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.isDisplayed = true')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();
    }
}

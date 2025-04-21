<?php

namespace App\Repository\Store;

use App\Entity\Message;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageStoreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @return Message[]
     */
    public function findActiveMessages(): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.isActive = true')
            ->getQuery()
            ->getResult();
    }
}

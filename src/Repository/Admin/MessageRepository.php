<?php

namespace App\Repository\Admin;

use App\Entity\Message;
use App\Enum\MessageType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function findOtherActiveMessagesByType(MessageType $type, int $exceptId): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.type = :type')
            ->andWhere('m.id != :id')
            ->andWhere('m.isActive = true')
            ->setParameter('type', $type->value)
            ->setParameter('id', $exceptId)
            ->getQuery()
            ->getResult();
    }
}

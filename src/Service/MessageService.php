<?php

namespace App\Service;

use App\Entity\Message;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

class MessageService
{
    public function __construct(
        private MessageRepository $messageRepository,
        private EntityManagerInterface $em
    ) {}

    /**
     * Désactive les autres messages du même type,
     * excepté celui qu’on vient de créer ou de mettre à jour.
     */

    public function disableOtherMessages(Message $current): void
    {
        $qb = $this->messageRepository->createQueryBuilder('m')
            ->where('m.type = :type')
            ->andWhere('m.id != :id')
            ->andWhere('m.isActive = true')
            ->setParameter('type', $current->getType())
            ->setParameter('id', $current->getId() ?? 0);

        $others = $qb->getQuery()->getResult();

        foreach ($others as $other) {
            $other->setIsActive(false);
            $this->em->persist($other);
        }
    }

}

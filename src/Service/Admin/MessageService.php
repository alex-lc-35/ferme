<?php

namespace App\Service\Admin;

use App\Entity\Message;
use App\Repository\Admin\MessageRepository;
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
        $id = $current->getId() ?? 0;

        $others = $this->messageRepository->findOtherActiveMessagesByType(
            $current->getType(),
            $id
        );

        foreach ($others as $other) {
            $other->setIsActive(false);
            $this->em->persist($other);
        }

    }
}

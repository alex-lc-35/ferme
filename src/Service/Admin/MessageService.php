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
     * Disable other messages of the same type when a new message is created or updated.
     *
     * @param Message $current The current message.
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

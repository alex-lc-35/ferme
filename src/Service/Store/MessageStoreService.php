<?php

namespace App\Service\Store;

use App\Dto\MessageDto;
use App\Mapper\MessageMapper;
use App\Repository\Store\MessageStoreRepository;

class MessageStoreService
{
    public function __construct(
        private MessageStoreRepository $messageRepository
    ) {}

    /**
     * @return MessageDto[]
     */
    public function getActiveMessages(): array
    {
        $messages = $this->messageRepository->findActiveMessages();

        return array_map(
            fn($m) => MessageMapper::toDto($m),
            $messages
        );
    }
}

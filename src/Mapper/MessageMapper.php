<?php

namespace App\Mapper;

use App\Dto\MessageDto;
use App\Entity\Message;

class MessageMapper
{
    public static function toDto(Message $message): MessageDto
    {
        return new MessageDto(
            id: $message->getId(),
            type: $message->getType()?->value,
            content: $message->getContent(),
            isActive: $message->isActive(),
        );
    }
}

<?php

namespace App\Dto\Message;

readonly class MessageDto
{
    public function __construct(
        public int $id,
        public string $type,
        public string $content,
        public bool $isActive,
    ) {}
}

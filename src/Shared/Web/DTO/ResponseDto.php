<?php

namespace App\Shared\Web\DTO;

final class ResponseDto
{
    public function __construct(
        private string $message = '',
        private bool $success = false,
        private int $entityId = 0,
        private array $data = []
    ) {}

    public function getMessage(): string
    {
        return $this->message;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getEntityId(): int
    {
        return $this->entityId;
    }

    public function getData(): array
    {
        return $this->data;
    }
}

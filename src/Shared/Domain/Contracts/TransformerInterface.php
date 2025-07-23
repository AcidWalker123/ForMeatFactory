<?php

namespace App\Shared\Domain\Contracts;

interface TransformerInterface
{
    public function toDTO(object $entity): object;
    public function collectionToArray(iterable $entities, string $expectedClass): array;
    public function toArray(object $entity): array;
}
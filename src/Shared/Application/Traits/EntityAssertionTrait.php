<?php

namespace App\Shared\Application\Traits;

trait EntityAssertionTrait
{
    protected function assertInstanceOf(object $entity, string $expectedClass): object
    {
        if (!$entity instanceof $expectedClass) {
            throw new \InvalidArgumentException("Expected instance of $expectedClass");
        }
        return $entity;
    }
}

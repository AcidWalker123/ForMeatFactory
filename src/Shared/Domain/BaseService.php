<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\ServiceInterface;
use App\Shared\Application\Traits\EntityAssertionTrait;

abstract class BaseService implements ServiceInterface
{
    use EntityAssertionTrait;
    protected function assertEntity(object $entity, string $expectedClass): object
    {
        return $this->assertInstanceOf($entity, $expectedClass);
    }
}
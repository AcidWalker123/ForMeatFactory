<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\TransformerInterface;
use App\Shared\Application\Traits\EntityAssertionTrait;

abstract class BaseTransformer implements TransformerInterface
{
    use EntityAssertionTrait;
    protected function assertEntity(object $entity, string $expectedClass): void
    {
        $this->assertInstanceOf($entity, $expectedClass);
    }

    public function collectionToArray(iterable $entities, string $expectedClass): array
    {
        $result = [];
        foreach ($entities as $entity) {
            $this->assertEntity($entity, $expectedClass);
            $result[] = $this->toArray($entity);
        }
        return $result;
    }
}

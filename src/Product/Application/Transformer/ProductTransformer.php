<?php

namespace App\Product\Application\Transformer;

use App\Product\Domain\Entity\Product;
use App\Product\Application\DTO\ProductDto;
use App\Shared\Domain\BaseTransformer;

class ProductTransformer extends BaseTransformer
{
    public function toDTO(object $entity): ProductDto
    {
        $this->assertEntity($entity, Product::class);

        return new ProductDto(
            $entity->getId(),
            $entity->getTitle(),
            $entity->getDescription(),
            $entity->getPrice(),
            $entity->getCategory(),
            $entity->isInStock()
        );
    }

    public function toArray(object $entity): array
    {
        $this->assertEntity($entity, Product::class);

        return [
            'id'          => $entity->getId(),
            'title'       => $entity->getTitle(),
            'description' => $entity->getDescription(),
            'price'       => (float)$entity->getPrice(),
            'category'    => $entity->getCategory(),
            'inStock'     => $entity->isInStock(),
        ];
    }

    public function fromCreateDto(ProductDto $dto): Product
    {
        return (new Product())
            ->setTitle($dto->getTitle())
            ->setDescription($dto->getDescription())
            ->setPrice($dto->getPrice())
            ->setCategory($dto->getCategory())
            ->setInStock($dto->isInStock());
    }
}

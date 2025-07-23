<?php

namespace App\Product\Application\DTO;

use App\Shared\Domain\BaseDto;

class ProductDto extends BaseDto
{
    public function __construct(
        private ?int $id,
        private string $title,
        private string $description,
        private float $price,
        private string $category,
        private bool $inStock
    ) {}
    public function getId(): int
    {
        return $this->id;
    }
    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCategory(): string
    {
        return $this->category;
    }

    public function isInStock(): bool
    {
        return $this->inStock;
    }
}

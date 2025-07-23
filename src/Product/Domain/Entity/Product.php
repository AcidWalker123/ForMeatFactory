<?php

namespace App\Product\Domain\Entity;

use App\Product\Infrastructure\Doctrine\Persistence\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\BaseEntity;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product extends BaseEntity
{
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    #[ORM\Column]
    private ?bool $inStock = null;

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    public function isInStock(): ?bool
    {
        return $this->inStock;
    }

    public function setInStock(bool $inStock): static
    {
        $this->inStock = $inStock;

        return $this;
    }
}

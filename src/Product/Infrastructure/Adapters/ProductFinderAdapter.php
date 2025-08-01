<?php

namespace App\Product\Infrastructure\Adapters;

use App\Order\Application\Port\ProductFinderInterface;
use App\Product\Infrastructure\Doctrine\Persistence\ProductRepository;
use App\Product\Domain\Entity\Product;

class ProductFinderAdapter implements ProductFinderInterface
{
    private ProductRepository $productRepository;

    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function findProductById(int $id): ?Product
    {
        return $this->productRepository->findOneBy(['id' => $id]);
    }
}

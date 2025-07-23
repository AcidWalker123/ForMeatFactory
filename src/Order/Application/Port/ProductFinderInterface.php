<?php

namespace App\Order\Application\Port;

use App\Product\Domain\Entity\Product;

interface ProductFinderInterface
{
    public function findProductById(int $id): ?Product;
}

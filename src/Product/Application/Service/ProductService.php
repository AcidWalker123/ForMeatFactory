<?php

namespace App\Product\Application\Service;

use App\Product\Application\Transformer\ProductTransformer;
use App\Product\Infrastructure\Doctrine\Persistence\ProductRepository;
use App\Shared\Domain\BaseDto;
use App\Shared\Web\DTO\ResponseDto;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use App\Product\Domain\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use App\Shared\Domain\BaseService;

class ProductService extends BaseService
{
    private const CACHE_KEY_ALL = 'products_all';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private ProductRepository $repository,
        private ProductTransformer $transformer,
        private CacheInterface $cacheProducts,
        private EntityManagerInterface $em
    ) {}

    public function getAllProducts(): ResponseDto
    {
        return $this->cacheProducts->get(self::CACHE_KEY_ALL, function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_TTL);

            $products = $this->repository->findAll();
            return new ResponseDto(
                message: 'Products retrieved successfully',
                success: true,
                data: $this->transformer->collectionToArray($products, Product::class)
            );
        });
    }

    public function create(BaseDto $dto): ResponseDto
    {
        try {
            $product = $this->transformer->fromCreateDto($dto);
            $this->em->persist($product);
            $this->em->flush();
            $this->clearCache();

            return new ResponseDto(
                entityId: $product->getId(),
                message: 'Product created successfully',
                success: true
            );
        } catch (\Throwable $e) {
            return new ResponseDto(
                message: 'Failed to create product: ' . $e->getMessage(),
                success: false
            );
        }
    }

    public function createMultiple(array $dtos): ResponseDto
    {
        $createdIds = [];
        $this->em->beginTransaction();

        try {
            foreach ($dtos as $dto) {
                if (!$dto instanceof CreateProductDto) {
                    throw new \InvalidArgumentException('Expected instance of CreateProductDto');
                }

                $product = $this->transformer->fromCreateDto($dto);
                $this->em->persist($product);
                $createdIds[] = $product;
            }

            $this->em->flush();
            $this->em->commit();
            
            $this->clearCache();

            return new ResponseDto(
                message: 'Products created successfully',
                success: true,
                data: array_map(fn(Product $p) => $p->getId(), $createdIds)
            );
        } catch (\Throwable $e) {
            $this->em->rollback();
            return new ResponseDto(
                message: 'Failed to create products: ' . $e->getMessage(),
                success: false
            );
        }
    }


    public function clearCache(): void
    {
        $this->cacheProducts->delete(self::CACHE_KEY_ALL);
    }
}

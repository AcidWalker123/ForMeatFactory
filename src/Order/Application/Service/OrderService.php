<?php

namespace App\Order\Application\Service;

use App\Order\Application\Port\ProductFinderInterface;
use App\Order\Application\Port\UserFinderInterface;
use App\Order\Application\Transformer\OrderTransformer;
use App\Order\Infrastructure\Doctrine\Persistence\OrderRepository;
use App\Order\Web\DTO\Request\CreateOrderDto;
use App\Shared\Domain\BaseDto;
use App\Shared\Domain\BaseService;
use App\Shared\Exception\ProductNotFoundException;
use App\Shared\Exception\UserNotFoundException;
use App\Shared\Web\DTO\ResponseDto;
use Doctrine\ORM\EntityManagerInterface;
use App\Order\Domain\Factory\OrderFactory;
use App\Order\Domain\Entity\Order;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class OrderService extends BaseService
{
    private const CACHE_KEY_PREFIX = 'orders_user_';
    private const CACHE_TTL = 3600; // 1 hour

    public function __construct(
        private UserFinderInterface $userFinder,
        private ProductFinderInterface $productFinder,
        private OrderRepository $orderRepository,
        private EntityManagerInterface $em,
        private OrderFactory $orderFactory,
        private OrderTransformer $orderTransformer,
        private CacheInterface $cacheOrders,
    ) {
    }

    public function create(BaseDto $dto): ResponseDto
    {
        $this->em->beginTransaction();
        $orderDto = $this->assertEntity($dto, CreateOrderDto::class);
        try {
            $order = $this->orderFactory->create($dto);

            $this->em->persist($order);
            $this->em->flush();
            $this->em->commit();

            $userId = $orderDto->getUserId();

            if ($userId !== null) {
                $this->cacheOrders->delete(self::CACHE_KEY_PREFIX . $userId);
            }

            return new ResponseDto(
                entityId: $order->getId(),
                message: 'Order created successfully',
                success: true
            );

        } catch (ProductNotFoundException | UserNotFoundException | \InvalidArgumentException $e) {
            $this->em->rollback();
            return new ResponseDto(
                message: $e->getMessage(),
                success: false
            );
        }
    }

    public function getOrderByUserId(int $userId): ResponseDto
    {
        return $this->cacheOrders->get(self::CACHE_KEY_PREFIX . $userId, function (ItemInterface $item) use ($userId) {
            $item->expiresAfter(self::CACHE_TTL);

            $user = $this->userFinder->findUserById($userId);
            if (!$user) {
                return new ResponseDto(
                    message: 'User not found',
                    success: false
                );
            }

            $orders = $user->getOrders();
            if (empty($orders)) {
                return new ResponseDto(
                    message: 'No orders found for this user',
                    success: false
                );
            }

            $result = $this->orderTransformer->collectionToArray($orders, Order::class);

            return new ResponseDto(
                entityId: $userId,
                data: $result,
                success: true
            );
        });
    }

    public function clearCacheForUser(int $userId): void
    {
        $this->cacheOrders->delete(self::CACHE_KEY_PREFIX . $userId);
    }
}

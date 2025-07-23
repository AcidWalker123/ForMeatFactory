<?php

namespace App\Order\Domain\Factory;

use App\Order\Application\Port\ProductFinderInterface;
use App\Order\Application\Port\UserFinderInterface;
use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderItem;
use App\Order\Web\DTO\Request\CreateOrderDto;
use App\Shared\Exception\ProductNotFoundException;
use App\Shared\Exception\UserNotFoundException;
use App\Shared\Domain\BaseFactory;
use App\Shared\Domain\BaseDto;
use App\Shared\Domain\BaseEntity;

class OrderFactory extends BaseFactory
{
    public function __construct(
        private UserFinderInterface $userFinder,
        private ProductFinderInterface $productFinder
    ) {}

    public function create(BaseDto $dto): BaseEntity
    {
        if (!$dto instanceof CreateOrderDto) {
            throw new \InvalidArgumentException('Expected CreateOrderDto.');
        }

        return $this->buildOrder($dto);
    }

    private function buildOrder(CreateOrderDto $dto): Order
    {
        $user = $this->userFinder->findUserById($dto->getUserId());
        if (!$user) {
            throw new UserNotFoundException('User not found');
        }

        $order = new Order();
        $order->setAppUser($user);
        $order->setComment($dto->getComment());

        $totalPrice = 0;

        foreach ($dto->getOrderItems() as $item) {
            $product = $this->productFinder->findProductById($item['product_id']);
            if (!$product) {
                throw new ProductNotFoundException('Product not found');
            }

            $orderItem = new OrderItem();
            $orderItem->setParentOrder($order);
            $orderItem->setProduct($product);
            $orderItem->setQuantity($item['quantity']);

            $order->addOrderItem($orderItem);
            $totalPrice += $product->getPrice() * $item['quantity'];
        }

        $order->setTotal($totalPrice);
        return $order;
    }
}

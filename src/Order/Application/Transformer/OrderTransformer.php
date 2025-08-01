<?php

namespace App\Order\Application\Transformer;

use App\Order\Domain\Entity\Order;
use App\Order\Domain\Entity\OrderItem;
use App\Shared\Domain\BaseTransformer;
use InvalidArgumentException;

class OrderTransformer extends BaseTransformer
{
    public function toDTO(object $entity): object
    {
        $this->assertEntity($entity, Order::class);

        return (object) $this->toArray($entity);
    }

    public function toArray(object $entity): array
    {
        $this->assertEntity($entity, Order::class);

        return [
            'id'       => $entity->getId(),
            'comment'  => $entity->getComment(),
            'total'    => (float)$entity->getTotal(),
            'user_id'  => $entity->getAppUser()?->getId(),
            'items'    => $this->transformItems($entity->getOrderItems()->toArray()),
        ];
    }

    private function transformItems(array $items): array
    {
        return array_map(static function (OrderItem $item) {
            return [
                'product_id'          => $item->getProduct()?->getId(),
                'product_title'       => $item->getProduct()?->getTitle(),
                'product_description' => $item->getProduct()?->getDescription(),
                'product_price'       => (float) $item->getProduct()?->getPrice(),
                'product_category'    => $item->getProduct()?->getCategory(),
                'in_stock'            => $item->getProduct()?->isInStock(),
                'quantity'            => $item->getQuantity(),
            ];
        }, $items);
    }
}

<?php

namespace App\Order\Web\DTO\Abstract;

use App\Shared\Domain\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseOrderDTO extends BaseDto
{
    #[Assert\NotBlank(message: "User ID is required.")]
    #[Assert\Type(type: "integer", message: "User ID must be an integer.")]
    #[Assert\Positive(message: "User ID must be a positive number.")]
    protected $userId;

    #[Assert\Length(
        max: 500,
        maxMessage: "Comment cannot exceed {{ limit }} characters."
    )]
    #[Assert\Type(type: "string", message: "Comment must be an String.")]
    #[Assert\NotBlank(message: "Comment is required.")]
    protected $comment;

    #[Assert\NotBlank(message: "Order items cannot be empty.")]
    #[Assert\Type(type: "array", message: "Order items must be an array.")]
    #[Assert\Count(
        min: 1,
        minMessage: "At least one order item is required."
    )]
    #[Assert\All([
        new Assert\Collection([
            'fields' => [
                'product_id' => [
                    new Assert\NotBlank(message: "Product ID is required."),
                    new Assert\Type(type: "integer", message: "Product ID must be an integer."),
                    new Assert\Positive(message: "Product ID must be a positive number.")
                ],
                'quantity' => [
                    new Assert\NotBlank(message: "Quantity is required."),
                    new Assert\Type(type: "integer", message: "Quantity must be an integer."),
                    new Assert\Positive(message: "Quantity must be a positive number.")
                ],
            ],
            'allowExtraFields' => false,
            'allowMissingFields' => false,
        ])
    ])]
    protected $orderItems;

    public function getOrderItems(): array
    {
        return $this->orderItems;
    }

    public function setOrderItems($orderItems): void
    {
        $this->orderItems = $orderItems;
    }

    public function getUserId(): ?int
    {
        return $this->userId;
    }

    public function setUserId($userId): void
    {
        $this->userId = $userId;
    }

    public function setComment($comment): void
    {
        $this->comment = $comment;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }
}

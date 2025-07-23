<?php

namespace App\Order\Domain\Entity;

use App\Order\Infrastructure\Doctrine\Persistence\OrderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use App\User\Domain\Entity\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Shared\Domain\BaseEntity;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
#[ORM\Table(name: '`order`')]
class Order extends BaseEntity
{
    #[ORM\ManyToOne(inversedBy: 'orders')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $appUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 5)]
    private ?string $total = null;

    /**
     * @var Collection<int, OrderItem>
     */
    #[ORM\OneToMany(targetEntity: OrderItem::class, cascade: ['persist'], mappedBy: 'parentOrder')]
    private Collection $orderItems;

    public function __construct()
    {
        $this->orderItems = new ArrayCollection();
    }

    public function getAppUser(): ?User
    {
        return $this->appUser;
    }

    public function setAppUser(?User $appUser): static
    {
        $this->appUser = $appUser;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * @return Collection<int, OrderItem>
     */
    public function getOrderItems(): Collection
    {
        return $this->orderItems;
    }

    public function addOrderItem(OrderItem $orderItem): static
    {
        if (!$this->orderItems->contains($orderItem)) {
            $this->orderItems->add($orderItem);
            $orderItem->setParentOrder($this);
        }

        return $this;
    }

    public function removeOrderItem(OrderItem $orderItem): static
    {
        if ($this->orderItems->removeElement($orderItem)) {
            // set the owning side to null (unless already changed)
            if ($orderItem->getParentOrder() === $this) {
                $orderItem->setParentOrder(null);
            }
        }

        return $this;
    }

    public function getTotal(): ?string
    {
        return $this->total;
    }

    public function setTotal(string $total): static
    {
        $this->total = $total;

        return $this;
    }
}

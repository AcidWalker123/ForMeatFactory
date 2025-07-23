<?php

namespace App\Shared\Domain;

use Doctrine\ORM\Mapping as ORM;

#[ORM\MappedSuperclass]
abstract class BaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    protected ?int $id = null;

    public function getId(): ?int
    {
        return $this->id;
    }
}

<?php

namespace App\Shared\Domain;

use App\Shared\Domain\BaseEntity;
use App\Shared\Domain\BaseDto;

abstract class BaseFactory
{
    abstract public function create(BaseDto $dto): BaseEntity;
}

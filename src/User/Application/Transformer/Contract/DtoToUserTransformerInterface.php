<?php

namespace App\User\Application\Transformer\Contract;

use App\User\Domain\Entity\User;
use App\User\Web\DTO\Request\RegisterDto;

interface DtoToUserTransformerInterface
{
    public function transform(RegisterDto $dto): User;
}
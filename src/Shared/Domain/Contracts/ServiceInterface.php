<?php

namespace App\Shared\Domain\Contracts;

use App\Shared\Domain\BaseDto;
use App\Shared\Web\DTO\ResponseDto;

interface ServiceInterface
{
    public function create(BaseDto $dto): ResponseDto;
}

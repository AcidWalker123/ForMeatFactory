<?php

namespace App\Shared\Domain\Contracts;

use Symfony\Component\Validator\Validator\ValidatorInterface;

interface ValidatableDTOInterface
{
    public function validate(ValidatorInterface $validator): array;
}

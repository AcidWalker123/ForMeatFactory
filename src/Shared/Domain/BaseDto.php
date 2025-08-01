<?php

namespace App\Shared\Domain;

use App\Shared\Domain\Contracts\ValidatableDTOInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseDto implements ValidatableDTOInterface
{
    public function validate(ValidatorInterface $validator): array
    {
        $errors = $validator->validate($this);

        if (count($errors) > 0) {
            $errorMessages = [];
            foreach ($errors as $error) {
                $errorMessages[] = $error->getMessage();
            }
            return $errorMessages;
        }

        return [];
    }
}

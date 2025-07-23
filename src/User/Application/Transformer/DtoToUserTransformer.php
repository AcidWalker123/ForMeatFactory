<?php

namespace App\User\Application\Transformer;

use App\User\Application\Transformer\Contract\DtoToUserTransformerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\User\Web\DTO\Request\RegisterDto;
use App\User\Domain\Entity\User;
final class DtoToUserTransformer implements DtoToUserTransformerInterface
{
    public function __construct(private readonly UserPasswordHasherInterface $hasher) {}

    public function transform(RegisterDto $dto): User
    {
        $user = new User();
        $user->setName($dto->getUsername());
        $user->setPhone($dto->getPhone());
        $user->setAdress($dto->getAdress());
        $hashedPassword = $this->hasher->hashPassword($user, $dto->getPassword());
        $user->setPassword($hashedPassword);
        return $user;
    }
}
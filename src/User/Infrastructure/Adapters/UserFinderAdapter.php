<?php

namespace App\User\Infrastructure\Adapters;

use App\Order\Application\Port\UserFinderInterface;
use App\User\Infrastructure\Doctrine\Persistence\UserRepository;
use App\User\Domain\Entity\User;

class UserFinderAdapter implements UserFinderInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function findUserById(int $id): ?User
    {
        return $this->userRepository->findOneBy(['id' => $id]);
    }
}

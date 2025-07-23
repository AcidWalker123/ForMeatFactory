<?php

namespace App\Order\Application\Port;

use App\User\Domain\Entity\User;

interface UserFinderInterface
{
    public function findUserById(int $id): ?User;
}

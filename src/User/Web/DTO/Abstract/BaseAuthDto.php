<?php

namespace App\User\Web\DTO\Abstract;

use App\Shared\Domain\BaseDto;
use Symfony\Component\Validator\Constraints as Assert;

abstract class BaseAuthDto extends BaseDto
{
    #[Assert\NotBlank(message: "Name should not be blank")]
    #[Assert\Type(type: "string", message: "Name must be an String.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "Name is too short", maxMessage: "Name is too long")]
    #[Assert\Regex(
        pattern: "/^[a-zA-Z\s\-]+$/",
        message: "Name can only contain letters, spaces, and dashes"
    )]
    protected $username;

    #[Assert\NotBlank]
    #[Assert\Type(type: "string", message: "Password must be an String.")]
    #[Assert\Length(min: 3, max: 50, minMessage: "Password is too short", maxMessage: "Password is too long")]
    protected $password;

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername($username): void
    {
        $this->username = $username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword($password): void
    {
        $this->password = $password;
    }
}
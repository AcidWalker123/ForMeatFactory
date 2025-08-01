<?php

namespace App\User\Web\DTO\Request;

use App\User\Web\DTO\Abstract\BaseAuthDto;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterDto extends BaseAuthDto
{
    #[Assert\NotBlank(message: "Phone number is required")]
    #[Assert\Regex(
        pattern: '/^\+?[0-9\s\-]{7,20}$/',
        message: "Phone number format is invalid"
    )]
    #[Assert\Type(type: "string", message: "Phone number must be an String.")]
    #[Assert\Length(min: 5, max: 15, minMessage: "Phone number is too short", maxMessage: "Phone number is too long")]
    private $phone;

    #[Assert\NotBlank(message: "Address is required")]
    #[Assert\Length(
        min: 5,
        max: 255,
        minMessage: "Address is too short",
        maxMessage: "Address is too long"
    )]
    #[Assert\Type(type: "string", message: "Adress must be an String.")]
    private $adress;

    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }
    public function setAdress(string $adress): void
    {
        $this->adress = $adress;
    }
    public function getAdress(): string
    {
        return $this->adress;
    }
    public function getPhone(): string
    {
        return $this->phone;
    }

}

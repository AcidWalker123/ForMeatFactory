<?php

namespace App\User\Application\Service;

use App\Shared\Domain\BaseDto;
use App\Shared\Web\DTO\ResponseDto;
use App\User\Infrastructure\Doctrine\Persistence\UserRepository;
use App\User\Web\DTO\Request\RegisterDto;
use App\User\Application\Transformer\DtoToUserTransformer;
use App\Shared\Domain\BaseService;

class UserService extends BaseService
{
    private UserRepository $userRepository;
    private DtoToUserTransformer $userDtoTransformer;
    public function __construct(
        UserRepository $userRepository,
        DtoToUserTransformer $userDtoTransformer) 
        {
        $this->userRepository = $userRepository;
        $this->userDtoTransformer = $userDtoTransformer;
    }

    public function create(BaseDto $dto): ResponseDto
    {
        try {
            $registerDto = $this->assertEntity($dto, RegisterDto::class);
            $user = $this->userDtoTransformer->transform($registerDto);

            if (
                $this->userRepository->findOneBy(['phone' => $user->getPhone()]) && 
                $this->userRepository->findOneBy(['adress' => $user->getAdress()])
            ) {
                return new ResponseDto(
                    message: 'User already exists',
                    success: false
                );
            }

            $this->userRepository->save($user);

            return new ResponseDto(
                entityId: $user->getId(),
                message: 'User registered successfully',
                success: true
            );
            
        } catch (\Throwable $e) {
            return new ResponseDto(
                message: 'Internal Server Error: ' . $e->getMessage(),
                success: false
            );
        }
    }
}

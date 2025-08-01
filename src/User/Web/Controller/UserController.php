<?php

namespace App\User\Web\Controller;

use App\User\Application\Service\UserService;
use App\User\Web\DTO\Request\RegisterDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/api', name: 'app_user')]
final class UserController extends AbstractController
{
    private UserService $userService;
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    #[Route('/register', name: 'app_user', methods: ['POST'])]
    public function register(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $content = $request->getContent();
        $data = json_decode($content, true);

        $dto = new RegisterDto();
        $dto->setUsername($data['username']);
        $dto->setPassword($data['password']);
        $dto->setPhone($data['phone']);
        $dto->setAdress($data['adress']);

        $erros = $dto->validate($validator);

        if (!empty($erros)) {
            return $this->json(['errors' => $erros], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->userService->create($dto);
        $message = $result->getMessage();

        if (str_contains($message, 'User already exists')) {
            return $this->json([
                'message' => $result->getMessage(),
                'success' => $result->isSuccess()
            ], Response::HTTP_CONFLICT);
        }

        return $this->json([
            "success" => $result->isSuccess(),
            "user_id" => $result->getEntityId()
        ], Response::HTTP_CREATED);
    }
}

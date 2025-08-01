<?php

namespace App\Order\Web\Controller;

use App\Order\Application\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use App\Order\Web\DTO\Request\CreateOrderDto;

#[Route('/api/orders', name: 'app_order')]
final class OrderController extends AbstractController
{
    private OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    #[Route('', name: '_create', methods: ['POST'])]
    public function create(Request $request, ValidatorInterface $validator): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $dto = new CreateOrderDto();
        $dto->setUserId($data['user_id'] ?? null);
        $dto->setComment($data['comment'] ?? null);
        $dto->setOrderItems($data['products'] ?? []);

        $errors = $dto->validate($validator);
        if (!empty($errors)) {
            return $this->json([
                'status' => 'error',
                'errors' => $errors,
            ], Response::HTTP_BAD_REQUEST);
        }

        $result = $this->orderService->create($dto);

        if (!$result->isSuccess()) {
            $message = $result->getMessage();
            if ($message == 'Product not found') {
                return $this->json([
                    'success' => $result->isSuccess(),
                    'message' => $result->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }
            if ($message == 'User not found') {
                return $this->json([
                    'success' => $result->isSuccess(),
                    'message' => $result->getMessage(),
                ], Response::HTTP_NOT_FOUND);
            }
            if (str_contains($message, 'Invalid data provided')) {
                return $this->json([
                    'success' => $result->isSuccess(),
                    'message' => $result->getMessage(),
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return $this->json([
            'success' => $result->isSuccess(),
            "order_id" => $result->getEntityId(),
        ], Response::HTTP_CREATED);
    }

    #[Route('', name: '_list', methods: ['GET'])]
    public function getOrdersByUser(Request $request): JsonResponse
    {
        $userId = $request->query->get('user_id');

        $result = $this->orderService->getOrderByUserId((int)$userId);
        $message = $result->getMessage();
        if ($message == 'Product not found') {
            return $this->json([
                'success' => $result->isSuccess(),
                'message' => $result->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }
        if ($message == 'User not found') {
            return $this->json([
                'success' => $result->isSuccess(),
                'message' => $result->getMessage(),
            ], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
            'success' => $result->isSuccess(),
            'data' => $result->getData(),
        ], Response::HTTP_OK);
    }
}

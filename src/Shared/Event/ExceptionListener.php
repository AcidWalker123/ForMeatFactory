<?php

namespace App\Shared\Event;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpFoundation\Response;

class ExceptionListener
{
    private LoggerInterface $logger;
    private string $env;

    public function __construct(LoggerInterface $logger, string $env)
    {
        $this->logger = $logger;
        $this->env = $env;
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $exception = $event->getThrowable();

        $this->logger->error('Exception: ' . $exception->getMessage(), [
            'exception' => $exception
        ]);

        $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        $message = 'Internal Server Error';

        if ($exception instanceof HttpExceptionInterface) {
            $statusCode = $exception->getStatusCode();

            // Разрешённые коды ошибок
            if (in_array($statusCode, [
                Response::HTTP_BAD_REQUEST,
                Response::HTTP_NOT_FOUND,
                Response::HTTP_UNPROCESSABLE_ENTITY,
                Response::HTTP_CONFLICT,
                Response::HTTP_CREATED,
                Response::HTTP_OK,
                Response::HTTP_UNAUTHORIZED
            ])) {
                $message = $exception->getMessage() ?: Response::$statusTexts[$statusCode];
            } else {
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }
        }

        // В dev режиме можем показать оригинальное сообщение
        if ($this->env === 'dev' && $statusCode === 500) {
            $message = $exception->getMessage();
        }

        $response = new JsonResponse([
            'success' => false,
            'message' => $message
        ], $statusCode);

        $event->setResponse($response);
    }
}

<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Exception;

use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiErrorResponse;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiJsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ApiExceptionListener
{
    public function __invoke(ExceptionEvent $event): void
    {
        if (!str_starts_with($event->getRequest()->getPathInfo(), '/api/')) {
            return;
        }

        $exception = $event->getThrowable();

        var_dump($exception->getMessage());
        if ($exception instanceof HttpExceptionInterface) {
            if ($exception->getPrevious() instanceof ValidationFailedException) {
                $response = ApiErrorResponse::fromInvalidValidationHttpException($exception);
            } else {
                $response = ApiErrorResponse::fromHttpException($exception);
            }
        } else {
            $response = ApiErrorResponse::create();
        }
        $event->setResponse(ApiJsonResponse::fromApiResponse($response));
    }
}

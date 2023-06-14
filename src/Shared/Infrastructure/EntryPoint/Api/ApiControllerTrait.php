<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api;

use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiJsonResponse;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\Exception\ValidationFailedException;

trait ApiControllerTrait
{
    private function jsonResponse(ApiResponse $apiJsonResponse): JsonResponse
    {
        return ApiJsonResponse::fromApiResponse($apiJsonResponse);
    }

    private function validateRequest(mixed $request): void
    {
        $violations = $this->validator->validate($request);
        if (0 !== $violations->count()) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, 'Bad request', new ValidationFailedException($request, $violations));
        }
    }
}

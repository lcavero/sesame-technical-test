<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api;

use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiJsonResponse;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiResponse;
use Symfony\Component\HttpFoundation\JsonResponse;

trait ApiControllerTrait
{
    private function jsonResponse(ApiResponse $apiJsonResponse): JsonResponse
    {
        return ApiJsonResponse::fromApiResponse($apiJsonResponse);
    }
}

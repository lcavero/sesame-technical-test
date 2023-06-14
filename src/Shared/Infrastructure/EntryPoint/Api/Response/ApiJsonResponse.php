<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

final class ApiJsonResponse extends JsonResponse
{
    public static function fromApiResponse(ApiResponse $apiResponse): self
    {
        return new self([
            'status' => $apiResponse->status(),
            'message' => $apiResponse->message(),
            'data' => $apiResponse->data(),
            'errors' => $apiResponse->errors(),
            'metadata' => $apiResponse->metadata()
        ], $apiResponse->status());
    }
}

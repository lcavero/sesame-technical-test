<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;

use Symfony\Component\HttpFoundation\Response;

final readonly class ApiDeletedResponse implements ApiResponse
{
    private function __construct(public array $metadata, public array $headers)
    {}

    public static function create(array $metadata = [], array $headers = []): self
    {
        return new self($metadata, $headers);
    }

    public function status(): int
    {
        return Response::HTTP_OK;
    }

    public function message(): string
    {
        return 'ok';
    }

    public function headers(): array
    {
        return $this->headers;
    }

    public function metadata(): array
    {
        return $this->metadata;
    }

    public function data(): null
    {
        return null;
    }

    public function errors(): array
    {
        return [];
    }
}

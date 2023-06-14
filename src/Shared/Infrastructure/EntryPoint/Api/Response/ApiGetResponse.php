<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;

use Symfony\Component\HttpFoundation\Response;

final readonly class ApiGetResponse implements ApiResponse
{
    private function __construct(public mixed $data, public array $metadata, public array $headers)
    {}

    public static function create(mixed $data = null, array $metadata = [], array $headers = []): self
    {
        return new self($data, $metadata, $headers);
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

    public function data(): mixed
    {
        return $this->data;
    }

    public function errors(): array
    {
        return [];
    }
}

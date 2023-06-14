<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\Validator\Exception\ValidationFailedException;

final readonly class ApiErrorResponse implements ApiResponse
{
    private function __construct(public int $status, public string $message, public array $errors, public array $headers)
    {}

    public static function create(int $status = Response::HTTP_INTERNAL_SERVER_ERROR, string $message = 'Unexpected error. Please try again later.', array $errors = [], array $headers = []): self
    {
        return new self($status, $message, $errors, $headers);
    }

    public static function fromHttpException(HttpExceptionInterface $exception): self
    {
        return new self($exception->getStatusCode(), $exception->getMessage(), [], $exception->getHeaders());
    }

    public static function fromInvalidValidationHttpException(HttpExceptionInterface $exception): self
    {
        $previous = $exception->getPrevious();
        assert($previous instanceof ValidationFailedException);

        return new self(Response::HTTP_BAD_REQUEST, 'Bad request', ValidationFailedExceptionViolationsMapper::map($previous), $exception->getHeaders());
    }

    public function data(): null
    {
        return null;
    }

    public function status(): int
    {
        return $this->status;
    }

    public function message(): string
    {
        return $this->message;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function metadata(): array
    {
        return [];
    }

    public function headers(): array
    {
        return $this->headers;
    }
}

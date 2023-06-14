<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\EntryPoint\Api\Response;


use Symfony\Component\Validator\Exception\ValidationFailedException;

final class ValidationFailedExceptionViolationsMapper
{
    public static function map(ValidationFailedException $exception): array
    {
        $errors = [];

        foreach ($exception->getViolations() as $violation) {
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $errors;
    }
}

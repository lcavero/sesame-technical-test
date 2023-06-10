<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\Email;

final class InvalidEmailException extends \Exception
{
    public static function fromValue(string $value): self
    {
        return new self(sprintf('The value "%s" is not a valid email.', $value));
    }
}

<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\Email;

use App\Shared\Domain\Exception\DomainException;

final class InvalidEmailException extends DomainException
{
    public static function fromValue(string $value): self
    {
        return self::create(sprintf('The value "%s" is not a valid email.', $value));
    }
}

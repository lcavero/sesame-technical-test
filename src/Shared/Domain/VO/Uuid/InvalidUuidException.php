<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\Uuid;

use App\Shared\Domain\Exception\DomainException;

final class InvalidUuidException extends DomainException
{
    public static function fromValue(string $value): self
    {
        return self::create(sprintf('The value "%s" is not a valid Uuid.', $value));
    }
}

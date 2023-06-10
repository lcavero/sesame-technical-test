<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\String;

use App\Shared\Domain\Exception\DomainException;

final class InvalidStringException extends DomainException
{
    public static function fromLengthExceeded(string $value, int $maxLength): self
    {
        return self::create(sprintf('The value "%s" exceeds the maximum length of %d.', $value, $maxLength));
    }

    public static function fromLengthNotReached(string $value, int $minLength): self
    {
        return self::create(sprintf('The value "%s" does not meet the minimum length of %d.', $value, $minLength));
    }
}

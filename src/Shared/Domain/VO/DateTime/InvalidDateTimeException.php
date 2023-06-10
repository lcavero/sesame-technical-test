<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\DateTime;

use App\Shared\Domain\Exception\DomainException;

final class InvalidDateTimeException extends DomainException
{
    public static function fromATOMValue(string $value): self
    {
        return self::create(sprintf('The value "%s" is not a valid ATOM datetime.', $value));
    }
}

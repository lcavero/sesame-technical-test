<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\String;

final class InvalidStringException extends \Exception
{
    public static function fromLengthExceeded(string $value, int $maxLength): self
    {
        return new self(sprintf('The value "%s" exceeds the maximum length of %d.', $value, $maxLength));
    }

    public static function fromLengthNotReached(string $value, int $minLength): self
    {
        return new self(sprintf('The value "%s" does not meet the minimum length of %d.', $value, $minLength));
    }
}

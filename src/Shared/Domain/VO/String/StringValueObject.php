<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\String;

readonly class StringValueObject
{
    const MIN_LENGTH = 1;
    const MAX_LENGTH = 255;

    protected final function __construct(public string $value)
    {
    }

    public final static function fromString(string $value): static
    {
        static::validate($value);
        return new static($value);
    }

    public static function validate(string $value): void
    {
        if (!self::meetsMinLength($value)) {
            throw InvalidStringException::fromLengthNotReached($value, static::MIN_LENGTH);
        }

        if (!self::meetsMaxLength($value)) {
            throw InvalidStringException::fromLengthExceeded($value, static::MAX_LENGTH);
        }
    }

    public final static function meetsMaxLength(string $value): bool
    {
        return mb_strlen($value) <= static::MAX_LENGTH;
    }

    public final static function meetsMinLength(string $value): bool
    {
        return mb_strlen($value) >= static::MIN_LENGTH;
    }

    public final function __toString(): string
    {
        return $this->value;
    }
}

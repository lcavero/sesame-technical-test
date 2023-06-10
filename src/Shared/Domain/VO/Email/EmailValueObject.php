<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\Email;

readonly class EmailValueObject
{
    final const EMAIL_PATTERN = "/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/";

    protected final function __construct(public string $value)
    {
    }

    public final static function fromString(string $value): static
    {
        if (!static::isValid($value)) {
            throw InvalidEmailException::fromValue($value);
        }
        return new static($value);
    }

    public final static function isValid(string $value): bool
    {
        return 1 === preg_match(self::EMAIL_PATTERN, $value);
    }

    public final function __toString(): string
    {
        return $this->value;
    }
}

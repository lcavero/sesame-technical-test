<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\Uuid;

use Symfony\Component\Uid\Uuid;

readonly class UuidValueObject
{
    final const UUID_PATTERN = '/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i';

    protected final function __construct(public string $value)
    {
    }

    public final static function generate(): static
    {
        return new static(Uuid::v4()->toRfc4122());
    }

    public final static function fromString(string $value): static
    {
        if (!static::isValid($value)) {
            throw InvalidUuidException::fromValue($value);
        }
        return new static($value);
    }

    public final function __toString(): string
    {
        return $this->value;
    }

    public final static function isValid(string $value): bool
    {
        return 1 === preg_match(self::UUID_PATTERN, $value);
    }
}

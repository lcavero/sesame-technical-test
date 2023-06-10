<?php declare(strict_types=1);

namespace App\Shared\Domain\VO\DateTime;

use DateTimeInterface;

readonly class DateTimeOrNullValueObject
{
    protected final function __construct(public ?\DateTimeImmutable $value)
    {
    }

    public static function fromDateTimeImmutable(?\DateTimeImmutable $value): static
    {
        return new static($value);
    }

    public final static function fromDateTime(?\DateTime $value): static
    {
        if (null === $value) {
            return self::fromDateTimeImmutable(null);
        }
        return self::fromDateTimeImmutable(\DateTimeImmutable::createFromMutable($value));
    }

    public final function __toString(): string
    {
        return $this->value ? $this->value->format(DateTimeInterface::ATOM) : '';
    }
}

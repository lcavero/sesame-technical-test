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

    public static function fromNull(): static
    {
        return self::fromDateTimeImmutable(null);
    }

    public final static function fromDateTime(?\DateTime $value): static
    {
        if (null === $value) {
            return self::fromDateTimeImmutable(null);
        }
        return self::fromDateTimeImmutable(\DateTimeImmutable::createFromMutable($value));
    }

    public final static function fromATOM(?string $value): static
    {
        if (null === $value) {
            return self::fromDateTimeImmutable(null);
        }
        $dateTime = \DateTimeImmutable::createFromFormat(DateTimeInterface::ATOM, $value);
        if (false === $dateTime) {
            throw InvalidDateTimeException::fromATOMValue($value);
        }
        return self::fromDateTimeImmutable($dateTime);
    }

    public final function toATOM(): ?string
    {
        return $this->value?->format(DateTimeInterface::ATOM);
    }

    public final function __toString(): string
    {
        return $this->toATOM() ?? '';
    }
}

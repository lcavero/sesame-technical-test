<?php declare(strict_types=1);

namespace Shared\Domain\VO\DateTime;

use App\Shared\Domain\VO\DateTime\DateTimeOrNullValueObject;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DateTimeOrNullValueObjectTest extends TestCase
{
    /** @dataProvider fromDateTimeImmutableSuccessfullyDataProvider */
    public function testFromDateTimeImmutableSuccessfully(?DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            $dateTime?->format(\DateTimeInterface::ATOM),
            DateTimeOrNullValueObject::fromDateTimeImmutable($dateTime)->value?->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider fromDateTimeSuccessfullyDataProvider */
    public function testFromDateTimeSuccessfully(?DateTime $dateTime): void
    {
        self::assertSame(
            $dateTime?->format(\DateTimeInterface::ATOM),
            DateTimeOrNullValueObject::fromDateTime($dateTime)->value?->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider toStringDataProvider */
    public function test__toString(?DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            null !== $dateTime ? $dateTime->format(\DateTimeInterface::ATOM) : '',
            DateTimeOrNullValueObject::fromDateTimeImmutable($dateTime)->__toString()
        );
    }

    public function fromDateTimeImmutableSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ],
            'Null' => [
                'value' => null
            ]
        ];
    }

    public function fromDateTimeSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTime::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ],
            'Null' => [
                'value' => null
            ]
        ];
    }

    public function toStringDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ],
            'Null' => [
                'value' => null
            ]
        ];
    }
}

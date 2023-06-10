<?php declare(strict_types=1);

namespace Shared\Domain\VO\DateTime;

use App\Shared\Domain\VO\DateTime\DateTimeOrNullValueObject;
use App\Shared\Domain\VO\DateTime\InvalidDateTimeException;
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

    /** @dataProvider fromATOMSuccessfullyDataProvider */
    public function testFromATOMSuccessfully(?string $dateTime): void
    {
        self::assertSame(
            $dateTime,
            DateTimeOrNullValueObject::fromATOM($dateTime)->value?->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider fromATOMShouldFailDataProvider */
    public function testFromATOMShouldFail(?string $dateTime): void
    {
        $this->expectException(InvalidDateTimeException::class);
        DateTimeOrNullValueObject::fromATOM($dateTime);
    }

    /** @dataProvider toATOMDataProvider */
    public function testToATOM(?DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            $dateTime?->format(\DateTimeInterface::ATOM),
            DateTimeOrNullValueObject::fromDateTimeImmutable($dateTime)->toATOM()
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

    public function toATOMDataProvider(): array
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

    public function fromATOMSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => '2021-01-03T02:30:00+01:00'
            ],
            'Null date' => [
                'value' => null
            ]
        ];
    }

    public function fromATOMShouldFailDataProvider(): array
    {
        return [
            'Invalid date' => [
                'value' => '12021-01-03T02:303:00+01:00'
            ],
            'Invalid format' => [
                'value' => '10/10/2020 10:03:00'
            ]
        ];
    }
}

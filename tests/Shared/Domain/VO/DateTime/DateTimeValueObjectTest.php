<?php declare(strict_types=1);

namespace Shared\Domain\VO\DateTime;

use App\Shared\Domain\VO\DateTime\DateTimeValueObject;
use App\Shared\Domain\VO\DateTime\InvalidDateTimeException;
use DateTime;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

final class DateTimeValueObjectTest extends TestCase
{
    /** @dataProvider fromDateTimeImmutableSuccessfullyDataProvider */
    public function testFromDateTimeImmutableSuccessfully(DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            $dateTime->format(\DateTimeInterface::ATOM),
            DateTimeValueObject::fromDateTimeImmutable($dateTime)->value->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider fromDateTimeSuccessfullyDataProvider */
    public function testFromDateTimeSuccessfully(DateTime $dateTime): void
    {
        self::assertSame(
            $dateTime->format(\DateTimeInterface::ATOM),
            DateTimeValueObject::fromDateTime($dateTime)->value->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider fromATOMSuccessfullyDataProvider */
    public function testFromATOMSuccessfully(string $dateTime): void
    {
        self::assertSame(
            $dateTime,
            DateTimeValueObject::fromATOM($dateTime)->value->format(\DateTimeInterface::ATOM)
        );
    }

    /** @dataProvider fromATOMShouldFailDataProvider */
    public function testFromATOMShouldFail(string $dateTime): void
    {
        $this->expectException(InvalidDateTimeException::class);
        DateTimeValueObject::fromATOM($dateTime);
    }

    /** @dataProvider toATOMDataProvider */
    public function testToATOM(DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            $dateTime->format(\DateTimeInterface::ATOM),
            DateTimeValueObject::fromDateTimeImmutable($dateTime)->__toString()
        );
    }

    /** @dataProvider toStringDataProvider */
    public function test__toString(DateTimeImmutable $dateTime): void
    {
        self::assertSame(
            $dateTime->format(\DateTimeInterface::ATOM),
            DateTimeValueObject::fromDateTimeImmutable($dateTime)->__toString()
        );
    }

    public function fromDateTimeImmutableSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ]
        ];
    }

    public function fromDateTimeSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTime::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ]
        ];
    }

    public function toStringDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ]
        ];
    }

    public function toATOMDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => DateTimeImmutable::createFromFormat(\DateTimeInterface::ATOM, '2021-01-03T02:30:00+01:00')
            ]
        ];
    }

    public function fromATOMSuccessfullyDataProvider(): array
    {
        return [
            'Any date' => [
                'value' => '2021-01-03T02:30:00+01:00'
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

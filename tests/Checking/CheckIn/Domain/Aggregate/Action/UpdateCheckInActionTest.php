<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Domain\Aggregate\Action;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInEndDateIsLowerThanStartDateException;
use PHPUnit\Framework\TestCase;

final class UpdateCheckInActionTest extends TestCase
{

    /** @dataProvider updateFailsBecauseEndDateIsLowerThanStartDateDataProvider */
    public function testUpdateFailsBecauseEndDateIsLowerThanStartDate(string $userId, string $startDate, string $endDate): void
    {
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b69'),
            startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            userId: CheckInUserId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b68'),
            createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: CheckInDeletedAt::fromNull()
        );
        $this->expectException(CheckInEndDateIsLowerThanStartDateException::class);
        $checkIn->update(
            startDate: CheckInStartDate::fromATOM($startDate),
            endDate: CheckInEndDate::fromATOM($endDate),
            userId: CheckInUserId::fromString($userId)
        );
    }

    /** @dataProvider updateSuccessfullyDataProvider */
    public function testCreateSuccessfully(string $id, string $userId, string $startDate, ?string $endDate): void
    {
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString($id),
            startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            userId: CheckInUserId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b68'),
            createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: CheckInDeletedAt::fromNull()
        );
        $checkIn->update(
            startDate: CheckInStartDate::fromATOM($startDate),
            endDate: CheckInEndDate::fromATOM($endDate),
            userId: CheckInUserId::fromString($userId)
        );

        self::assertSame($id, $checkIn->id()->value);
        self::assertSame($userId, $checkIn->userId()->value);
        self::assertNotNull($checkIn->createdAt()->toATOM());
        self::assertNotNull($checkIn->updatedAt()->toATOM());
        self::assertNull($checkIn->deletedAt()->toATOM());
        self::assertSame($startDate, $checkIn->startDate()->toATOM());
        self::assertSame($endDate, $checkIn->endDate()->toATOM());
    }

    public function updateSuccessfullyDataProvider(): array
    {
        return [
            'With all fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'userId' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'startDate' => '2023-01-03T02:35:00+01:00',
                'endDate' => '2025-01-03T02:35:00+01:00',
            ],
            'With optional fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'userId' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'startDate' => '2023-01-03T02:35:00+01:00',
                'endDate' => null,
            ],
        ];
    }

    public function updateFailsBecauseEndDateIsLowerThanStartDateDataProvider(): array
    {
        return [
            'End date lower than start date' => [
                'userId' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'createdAt' => '2020-01-03T02:30:00+01:00',
                'updatedAt' => '2020-01-02T02:30:00+01:00',
            ]
        ];
    }
}

<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Domain\Aggregate\Action;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInEndDateIsLowerThanStartDateException;
use PHPUnit\Framework\TestCase;

final class CreateCheckInActionTest extends TestCase
{
    /** @dataProvider creationFailsBecauseEndDateIsLowerThanStartDateDataProvider */
    public function testCreationFailsBecauseEndDateIsLowerThanStartDate(string $id, string $userId, string $startDate, string $endDate): void
    {
        $this->expectException(CheckInEndDateIsLowerThanStartDateException::class);
        CheckIn::create(
            id: CheckInId::fromString($id),
            startDate: CheckInStartDate::fromATOM($startDate),
            endDate: CheckInEndDate::fromATOM($endDate),
            userId: CheckInUserId::fromString($userId),
        );
    }


    /** @dataProvider createSuccessfullyDataProvider */
    public function testCreateSuccessfully(string $id, string $userId, string $startDate, ?string $endDate): void
    {
        $checkIn = CheckIn::create(
            id: CheckInId::fromString($id),
            startDate: CheckInStartDate::fromATOM($startDate),
            endDate: CheckInEndDate::fromATOM($endDate),
            userId: CheckInUserId::fromString($userId),
        );

        self::assertSame($id, $checkIn->id()->value);
        self::assertSame($userId, $checkIn->userId()->value);
        self::assertNotNull($checkIn->createdAt()->toATOM());
        self::assertNotNull($checkIn->updatedAt()->toATOM());
        self::assertNull($checkIn->deletedAt()->toATOM());
        self::assertSame($startDate, $checkIn->startDate()->toATOM());
        self::assertSame($endDate, $checkIn->endDate()->toATOM());
    }

    public function createSuccessfullyDataProvider(): array
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

    public function creationFailsBecauseEndDateIsLowerThanStartDateDataProvider(): array
    {
        return [
            'End date lower than start date' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'userId' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'startDate' => '2023-01-03T02:35:00+01:00',
                'endDate' => '2021-01-03T02:35:00+01:00',
            ],
        ];
    }
}

<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Domain\Aggregate;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use PHPUnit\Framework\TestCase;

final class CheckInTest extends TestCase
{
    /** @dataProvider fromValuesSuccessfullyDataProvider */
    public function testFromValuesSuccessfully(string $id, string $userId, string $createdAt, string $updatedAt,
                                            ?string $deletedAt, string $startDate, ?string $endDate): void
    {
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString($id),
            startDate: CheckInStartDate::fromATOM($startDate),
            endDate: CheckInEndDate::fromATOM($endDate),
            userId: CheckInUserId::fromString($userId),
            createdAt: CheckInCreatedAt::fromATOM($createdAt),
            updatedAt: CheckInUpdatedAt::fromATOM($updatedAt),
            deletedAt: CheckInDeletedAt::fromATOM($deletedAt)
        );

        self::assertSame($id, $checkIn->id()->value);
        self::assertSame($userId, $checkIn->userId()->value);
        self::assertSame($createdAt, $checkIn->createdAt()->toATOM());
        self::assertSame($updatedAt, $checkIn->updatedAt()->toATOM());
        self::assertSame($deletedAt, $checkIn->deletedAt()->toATOM());
        self::assertSame($startDate, $checkIn->startDate()->toATOM());
        self::assertSame($endDate, $checkIn->endDate()->toATOM());
    }

    public function fromValuesSuccessfullyDataProvider(): array
    {
        return [
            'With all fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'userId' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'createdAt' => '2019-01-03T02:35:00+01:00',
                'updatedAt' => '2020-01-03T02:35:00+01:00',
                'deletedAt' => '2022-01-03T02:35:00+01:00',
                'startDate' => '2023-01-03T02:35:00+01:00',
                'endDate' => '2025-01-03T02:35:00+01:00',
            ],
            'With optional fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'userId' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'createdAt' => '2019-01-03T02:35:00+01:00',
                'updatedAt' => '2020-01-03T02:35:00+01:00',
                'deletedAt' => null,
                'startDate' => '2023-01-03T02:35:00+01:00',
                'endDate' => null,
            ],
        ];
    }
}

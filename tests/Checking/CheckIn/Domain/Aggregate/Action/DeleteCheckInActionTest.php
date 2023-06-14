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
use PHPUnit\Framework\TestCase;

final class DeleteCheckInActionTest extends TestCase
{

    /** @dataProvider deleteSuccessfullyDataProvider */
    public function testDeleteSuccessfully(string $id): void
    {
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString($id),
            startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            userId: CheckInUserId::generate(),
            createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: CheckInDeletedAt::fromNull()
        );

        $checkIn->delete();

        self::assertNotNull($checkIn->deletedAt()->value);
    }

    public function deleteSuccessfullyDataProvider(): array
    {
        return [
            'Standard fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
            ],
        ];
    }
}

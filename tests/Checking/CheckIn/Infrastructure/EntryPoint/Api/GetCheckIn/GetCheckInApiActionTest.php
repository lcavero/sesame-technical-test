<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\GetCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class GetCheckInApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/check-ins/%s/';

    public function testGetCheckInApiActionFailsBecauseCheckInNotExists(): void
    {
        $this->client->request('GET', sprintf(self::uri, '15342d22-d819-460a-a662-b679a7fe89b2'));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'id' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" should exists'
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testGetCheckInApiActionSuccessfully(): void
    {
        $repository = self::getContainer()->get(CheckInRepository::class);
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b67'),
            startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            userId: CheckInUserId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b68'),
            createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: CheckInDeletedAt::fromNull()
        );

        $repository->save($checkIn);

        $this->client->request('GET', sprintf(self::uri, $checkIn->id()->value));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

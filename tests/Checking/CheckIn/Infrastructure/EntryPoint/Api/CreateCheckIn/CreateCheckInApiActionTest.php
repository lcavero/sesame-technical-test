<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\CreateCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Tests\BaseWebTestCase;
use Symfony\Component\HttpFoundation\Response;

final class CreateCheckInApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/check-ins/';

    public function testCreateCheckInApiActionWithFailures(): void
    {
        $repository = self::getContainer()->get(CheckInRepository::class);
        $checkIn = CheckIn::fromValues(
            id: CheckInId::fromString('15342d22-d819-460a-a662-b679a7fe89b2'),
            startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
            userId: CheckInUserId::fromString('15342d22-d819-460a-a662-b679a7fe89b3'),
            createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: CheckInDeletedAt::fromDateTimeImmutable(new \DateTimeImmutable())
        );

        $repository->save($checkIn);

        $body = [
            'id' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'userId' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'startDate' => '2020-05-02 10:00:00',
            'endDate' => '2020-05-02 10:00:00',
        ];
        $this->client->request('POST', self::uri, $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'id' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" already exists',
            'userId' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" should exists',
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testCreateCheckInApiActionSuccessfully(): void
    {
        $repository = self::getContainer()->get(UserRepository::class);
        $user = User::fromValues(
            id: UserId::fromString('15342d22-d819-460a-a662-b679a7fe89b2'),
            name: UserName::fromString('Test'),
            email: UserEmail::fromString('test@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        );

        $repository->save($user);


        $body = [
            'id' => 'd0643b8e-1cbb-4f60-84fe-7aa3981297e6',
            'userId' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'startDate' => '2020-05-02 10:00:00',
            'endDate' => '2020-05-02 10:00:00',
        ];
        $this->client->request('POST', self::uri, $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}

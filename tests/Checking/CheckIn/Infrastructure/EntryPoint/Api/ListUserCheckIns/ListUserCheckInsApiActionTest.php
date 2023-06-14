<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns;

use App\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns\ListUserCheckInsApiAction;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Tests\BaseWebTestCase;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;

final class ListUserCheckInsApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/check-ins/user/%s/';

    public function testCreateUserApiActionSuccessfully(): void
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

        $this->client->request('GET', sprintf(self::uri, '15342d22-d819-460a-a662-b679a7fe89b2'));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

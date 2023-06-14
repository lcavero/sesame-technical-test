<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\DeleteUser;

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

final class DeleteUserApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/users/%s/';

    public function testDeleteUserApiActionFailsBecauseUserNotExists(): void
    {
        $this->client->request('DELETE', sprintf(self::uri, '15342d22-d819-460a-a662-b679a7fe89b2'));

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'id' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" should exists'
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testDeleteUserApiActionSuccessfully(): void
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

        $this->client->request('DELETE', sprintf(self::uri, $user->id()->value));

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

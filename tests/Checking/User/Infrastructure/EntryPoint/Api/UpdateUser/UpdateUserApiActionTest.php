<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

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

final class UpdateUserApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/users/%s/';

    public function testUpdateUserApiActionFailsBecauseEmailAlreadyExists(): void
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

        $user2 = User::fromValues(
            id: UserId::fromString('c0cf3e51-f4e8-435d-8a00-3042de0743e6'),
            name: UserName::fromString('Test2'),
            email: UserEmail::fromString('test2@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        );

        $repository->save($user2);

        $body = [
            'name' => 'Changed',
            'email' => $user->email()->value
        ];
        $this->client->request('PUT', sprintf(self::uri, $user2->id()->value,), $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'email' => 'An entity with email.value equals to "' . $user->email()->value . '" already exists'
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testUpdateUserApiActionFailsBecauseUserNotExists(): void
    {
        $body = [
            'name' => 'test',
            'email' => 'test@test.com'
        ];
        $this->client->request('PUT', sprintf(self::uri, '15342d22-d819-460a-a662-b679a7fe89b2'), $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'id' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" should exists'
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testUpdateUserApiActionSuccessfully(): void
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

        $user2 = User::fromValues(
            id: UserId::fromString('c0cf3e51-f4e8-435d-8a00-3042de0743e6'),
            name: UserName::fromString('Test2'),
            email: UserEmail::fromString('test2@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        );

        $repository->save($user2);

        $body = [
            'name' => 'Changed',
            'email' => $user2->email()->value
        ];
        $this->client->request('PUT', sprintf(self::uri, $user2->id()->value), $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}

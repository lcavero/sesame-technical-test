<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Infrastructure\EntryPoint\Api\CreateUser;

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

final class CreateUserApiActionTest extends BaseWebTestCase
{
    const uri = '/api/checking/users/';

    public function testCreateUserApiActionWithFailures(): void
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
            'id' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'name' => 'test',
            'email' => 'test@test.com'
        ];
        $this->client->request('POST', self::uri, $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);

        $errors = [
            'id' => 'An entity with id equals to "15342d22-d819-460a-a662-b679a7fe89b2" already exists',
            'email' => 'An entity with email.value equals to "test@test.com" already exists'
        ];

        self::assertSame($errors, $this->getResponse()['errors'] ?? null);
    }

    public function testCreateUserApiActionSuccessfully(): void
    {
        $body = [
            'id' => '15342d22-d819-460a-a662-b679a7fe89b2',
            'name' => 'test',
            'email' => 'test@test.com'
        ];
        $this->client->request('POST', self::uri, $body);

        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
    }
}

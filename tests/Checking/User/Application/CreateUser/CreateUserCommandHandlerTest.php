<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Application\CreateUser;

use App\Checking\User\Application\CreateUser\CreateUserCommand;
use App\Checking\User\Application\CreateUser\CreateUserCommandHandler;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Exception\UserAlreadyExistsException;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\VO\Uuid\UuidValueObject;
use PHPUnit\Framework\TestCase;

final class CreateUserCommandHandlerTest extends TestCase
{
    public function testCreationFailedBecauseUserWithSameIdAlreadyExists(): void
    {
        $command = CreateUserCommand::create(
            id: UuidValueObject::generate()->value,
            name: 'Test',
            email: 'test@test.com'
        );

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(User::fromValues(
                id: UserId::generate(),
                name: UserName::fromString('Test'),
                email: UserEmail::fromString('test@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;

        $commandHandler = new CreateUserCommandHandler($repositoryMock);
        $this->expectException(UserAlreadyExistsException::class);
        $commandHandler->__invoke($command);
    }

    public function testCreationFailedBecauseUserWithSameEmailAlreadyExists(): void
    {
        $command = CreateUserCommand::create(
            id: UuidValueObject::generate()->value,
            name: 'Test',
            email: 'test@test.com'
        );

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(null)
        ;

        $repositoryMock->expects($this->once())
            ->method('findOneByEmail')
            ->willReturn(User::fromValues(
                id: UserId::generate(),
                name: UserName::fromString('Test'),
                email: UserEmail::fromString('test@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;

        $commandHandler = new CreateUserCommandHandler($repositoryMock);
        $this->expectException(UserAlreadyExistsException::class);
        $commandHandler->__invoke($command);
    }

    public function testCreateUserCommandHandlerSuccessfully(): void
    {
        $command = CreateUserCommand::create(
            id: UuidValueObject::generate()->value,
            name: 'Test',
            email: 'test@test.com'
        );

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('save')
            ->with(self::callback(function ($user) use ($command): bool {
                self::assertInstanceOf(User::class, $user);
                self::assertSame($command->id, $user->id()->value);
                self::assertSame($command->name, $user->name()->value);
                self::assertSame($command->email, $user->email()->value);
                return true;
            }))
        ;

        $commandHandler = new CreateUserCommandHandler($repositoryMock);

        $commandHandler->__invoke($command);
    }
}

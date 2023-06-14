<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Application\UpdateUser;

use App\Checking\User\Application\UpdateUser\UpdateUserCommand;
use App\Checking\User\Application\UpdateUser\UpdateUserCommandHandler;
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

final class UpdateUserCommandHandlerTest extends TestCase
{
    public function testUpdateFailedBecauseUserWithSameEmailAlreadyExists(): void
    {
        $command = UpdateUserCommand::create(
            id: UuidValueObject::generate()->value,
            name: 'Test',
            email: 'test2@test.com'
        );

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneByIdOrFail')
            ->willReturn(User::fromValues(
                id: UserId::generate(),
                name: UserName::fromString('Test'),
                email: UserEmail::fromString('test@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;

        $repositoryMock->expects($this->once())
            ->method('findOneByEmail')
            ->willReturn(User::fromValues(
                id: UserId::generate(),
                name: UserName::fromString('Test2'),
                email: UserEmail::fromString('test2@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;

        $commandHandler = new UpdateUserCommandHandler($repositoryMock);
        $this->expectException(UserAlreadyExistsException::class);
        $commandHandler->__invoke($command);
    }

    public function testUpdateUserCommandHandlerSuccessfully(): void
    {
        $command = UpdateUserCommand::create(
            id: UuidValueObject::generate()->value,
            name: 'Test2',
            email: 'test2@test.com'
        );

        $repositoryMock = $this->createMock(UserRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneByIdOrFail')
            ->willReturn(User::fromValues(
                id: UserId::fromString($command->id),
                name: UserName::fromString('Test'),
                email: UserEmail::fromString('test@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;
        $repositoryMock->expects($this->once())
            ->method('save')
            ->with(self::callback(function ($user) use ($command): bool {
                self::assertInstanceOf(User::class, $user);
                self::assertSame($command->name, $user->name()->value);
                self::assertSame($command->email, $user->email()->value);
                return true;
            }))
        ;

        $commandHandler = new UpdateUserCommandHandler($repositoryMock);

        $commandHandler->__invoke($command);
    }
}

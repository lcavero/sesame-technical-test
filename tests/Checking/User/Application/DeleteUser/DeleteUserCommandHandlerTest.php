<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Application\DeleteUser;

use App\Checking\User\Application\DeleteUser\DeleteUserCommandHandler;
use App\Checking\User\Application\DeleteUser\DeleteUserCommand;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\VO\Uuid\UuidValueObject;
use PHPUnit\Framework\TestCase;

final class DeleteUserCommandHandlerTest extends TestCase
{

    public function testDeleteUserCommandHandlerSuccessfully(): void
    {
        $command = DeleteUserCommand::create(
            id: UuidValueObject::generate()->value,
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
                self::assertNotNull($user->deletedAt()->value);
                return true;
            }))
        ;

        $commandHandler = new DeleteUserCommandHandler($repositoryMock);

        $commandHandler->__invoke($command);
    }
}

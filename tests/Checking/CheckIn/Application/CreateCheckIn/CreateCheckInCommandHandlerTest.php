<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Application\CreateCheckIn;

use App\Checking\CheckIn\Application\CreateCheckIn\CreateCheckInCommand;
use App\Checking\CheckIn\Application\CreateCheckIn\CreateCheckInCommandHandler;
use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInAlreadyExistsException;
use App\Checking\CheckIn\Domain\Exception\CheckInUserIdInNotFoundException;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
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

final class CreateCheckInCommandHandlerTest extends TestCase
{
    public function testCreationFailedBecauseCheckInWithSameIdAlreadyExists(): void
    {
        $command = CreateCheckInCommand::create(
            id: UuidValueObject::generate()->value,
            userId: UuidValueObject::generate()->value,
            startDate: new \DateTimeImmutable(),
            endDate: null
        );

        $checkInRepositoryMock = $this->createMock(CheckInRepository::class);
        $checkInRepositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(CheckIn::fromValues(
                id: CheckInId::fromString($command->id),
                startDate: CheckInStartDate::fromDateTimeImmutable($command->startDate),
                endDate: CheckInEndDate::fromDateTimeImmutable($command->endDate),
                userId: CheckInUserId::fromString($command->userId),
                createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: CheckInDeletedAt::fromDateTimeImmutable(new \DateTimeImmutable())
            ))
        ;

        $userRepositoryMock = $this->createMock(UserRepository::class);

        $commandHandler = new CreateCheckInCommandHandler($checkInRepositoryMock, $userRepositoryMock);
        $this->expectException(CheckInAlreadyExistsException::class);
        $commandHandler->__invoke($command);
    }

    public function testCreationFailedBecauseUserIdNotExists(): void
    {
        $command = CreateCheckInCommand::create(
            id: UuidValueObject::generate()->value,
            userId: UuidValueObject::generate()->value,
            startDate: new \DateTimeImmutable(),
            endDate: null
        );

        $checkInRepositoryMock = $this->createMock(CheckInRepository::class);
        $checkInRepositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(null)
        ;

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(null)
        ;

        $commandHandler = new CreateCheckInCommandHandler($checkInRepositoryMock, $userRepositoryMock);
        $this->expectException(CheckInUserIdInNotFoundException::class);
        $commandHandler->__invoke($command);
    }

    /** @dataProvider createCheckInCommandHandlerSuccessfullyDataProvider */
    public function testCreateCheckInCommandHandlerSuccessfully(string $id, string $userId, \DateTimeImmutable $startDate, ?\DateTimeImmutable $endDate): void
    {
        $command = CreateCheckInCommand::create(
            id: $id,
            userId: $userId,
            startDate: $startDate,
            endDate: $endDate
        );

        $checkInRepositoryMock = $this->createMock(CheckInRepository::class);
        $checkInRepositoryMock->expects($this->once())
            ->method('save')
            ->with(self::callback(function ($checkIn) use ($command): bool {
                self::assertInstanceOf(CheckIn::class, $checkIn);
                self::assertSame($command->id, $checkIn->id()->value);
                self::assertSame($command->userId, $checkIn->userId()->value);
                self::assertSame($command->startDate->format(\DateTimeInterface::ATOM), $checkIn->startDate()->toATOM());
                self::assertSame($command->endDate?->format(\DateTimeInterface::ATOM), $checkIn->endDate()->toATOM());
                return true;
            }))
        ;

        $userRepositoryMock = $this->createMock(UserRepository::class);
        $userRepositoryMock->expects($this->once())
            ->method('findOneById')
            ->willReturn(User::fromValues(
                id: UserId::fromString($command->id),
                name: UserName::fromString('Test'),
                email: UserEmail::fromString('test@test.com'),
                createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: UserDeletedAt::fromNull()
            ))
        ;

        $commandHandler = new CreateCheckInCommandHandler($checkInRepositoryMock, $userRepositoryMock);

        $commandHandler->__invoke($command);
    }

    public function createCheckInCommandHandlerSuccessfullyDataProvider(): array
    {
        return [
            'With all fields' => [
                'id' => '047d5722-ca35-4b9d-93f0-04eebf0f0f5a',
                'userId' => '4464b991-11fd-4e57-a51e-162f3790395e',
                'startDate' => new \DateTimeImmutable(),
                'endDate' => new \DateTimeImmutable()
            ],
            'With optional fields' => [
                'id' => '047d5722-ca35-4b9d-93f0-04eebf0f0f5a',
                'userId' => '4464b991-11fd-4e57-a51e-162f3790395e',
                'startDate' => new \DateTimeImmutable(),
                'endDate' => null
            ],
        ];
    }
}

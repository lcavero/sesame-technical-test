<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Application\UpdateCheckIn;

use App\Checking\CheckIn\Application\UpdateCheckIn\UpdateCheckInCommand;
use App\Checking\CheckIn\Application\UpdateCheckIn\UpdateCheckInCommandHandler;
use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use App\Checking\User\Domain\Repository\UserRepository;
use PHPUnit\Framework\TestCase;

final class UpdateCheckInCommandHandlerTest extends TestCase
{
    /** @dataProvider updateCheckInCommandHandlerSuccessfullyDataProvider */
    public function UpdateCheckInCommandHandlerSuccessfully(string $id, string $userId, \DateTimeImmutable $startDate, ?\DateTimeImmutable $endDate): void
    {
        $command = UpdateCheckInCommand::create(
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

        $commandHandler = new UpdateCheckInCommandHandler($checkInRepositoryMock, $userRepositoryMock);

        $commandHandler->__invoke($command);
    }

    public function updateCheckInCommandHandlerSuccessfullyDataProvider(): array
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

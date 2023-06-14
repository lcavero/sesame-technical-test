<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Application\DeleteCheckIn;

use App\Checking\CheckIn\Application\DeleteCheckIn\DeleteCheckInCommand;
use App\Checking\CheckIn\Application\DeleteCheckIn\DeleteCheckInCommandHandler;
use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInCreatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInDeletedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUpdatedAt;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Shared\Domain\VO\Uuid\UuidValueObject;
use PHPUnit\Framework\TestCase;

final class DeleteCheckInCommandHandlerTest extends TestCase
{

    public function testDeleteCheckInCommandHandlerSuccessfully(): void
    {
        $command = DeleteCheckInCommand::create(
            id: UuidValueObject::generate()->value,
        );

        $repositoryMock = $this->createMock(CheckInRepository::class);
        $repositoryMock->expects($this->once())
            ->method('findOneByIdOrFail')
            ->willReturn(CheckIn::fromValues(
                id: CheckInId::fromString($command->id),
                startDate: CheckInStartDate::fromDateTimeImmutable(new \DateTimeImmutable()),
                endDate: CheckInEndDate::fromDateTimeImmutable(new \DateTimeImmutable()),
                userId: CheckInUserId::fromString('5c4130c3-ae89-429c-9474-9eb49e329b68'),
                createdAt: CheckInCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                updatedAt: CheckInUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
                deletedAt: CheckInDeletedAt::fromNull()
            ))
        ;
        $repositoryMock->expects($this->once())
            ->method('save')
            ->with(self::callback(function ($checkIn): bool {
                self::assertInstanceOf(CheckIn::class, $checkIn);
                self::assertNotNull($checkIn->deletedAt()->value);
                return true;
            }))
        ;

        $commandHandler = new DeleteCheckInCommandHandler($repositoryMock);

        $commandHandler->__invoke($command);
    }
}

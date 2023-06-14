<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\CreateCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInAlreadyExistsException;
use App\Checking\CheckIn\Domain\Exception\CheckInUserIdInNotFoundException;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class CreateCheckInCommandHandler implements CommandHandler
{
    public function __construct(private CheckInRepository $checkInRepository, private UserRepository $userRepository)
    {
    }


    public function __invoke(CreateCheckInCommand $command): void
    {
        if (null !== $this->checkInRepository->findOneById(CheckInId::fromString($command->id))) {
            throw CheckInAlreadyExistsException::create(sprintf('A check-in with id "%s" already exists.', $command->id));
        }

        if (null === $this->userRepository->findOneById(UserId::fromString($command->userId))) {
            throw CheckInUserIdInNotFoundException::create(sprintf('The user with id "%s" not exists.', $command->userId));
        }

        $checkIn = CheckIn::create(
            id: CheckInId::fromString($command->id),
            startDate: CheckInStartDate::fromDateTimeImmutable($command->startDate),
            endDate: CheckInEndDate::fromDateTimeImmutable($command->endDate),
            userId: CheckInUserId::fromString( $command->userId)
        );

        $this->checkInRepository->save($checkIn);
    }
}

<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\UpdateCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckInEndDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInStartDate;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\CheckIn\Domain\Exception\CheckInUserIdInNotFoundException;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class UpdateCheckInCommandHandler implements CommandHandler
{
    public function __construct(private CheckInRepository $checkInRepository, private UserRepository $userRepository)
    {
    }

    public function __invoke(UpdateCheckInCommand $command): void
    {
        $checkIn = $this->checkInRepository->findOneByIdOrFail(CheckInId::fromString($command->id));

        if (null === $this->userRepository->findOneById(UserId::fromString($command->userId))) {
            throw CheckInUserIdInNotFoundException::create(sprintf('The user with id "%s" not exists.', $command->userId));
        }

        $checkIn->update(
            startDate: CheckInStartDate::fromDateTimeImmutable($command->startDate),
            endDate: CheckInEndDate::fromDateTimeImmutable($command->endDate),
            userId: CheckInUserId::fromString($command->userId)
        );

        $this->checkInRepository->save($checkIn);
    }
}

<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\DeleteCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Repository\CheckInRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class DeleteCheckInCommandHandler implements CommandHandler
{
    public function __construct(private CheckInRepository $checkInRepository)
    {
    }

    public function __invoke(DeleteCheckInCommand $command): void
    {
        $checkIn = $this->checkInRepository->findOneByIdOrFail(CheckInId::fromString($command->id));

        $checkIn->delete();

        $this->checkInRepository->save($checkIn);
    }
}

<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\CreateCheckIn;

use App\Shared\Domain\Bus\Command\Command;

final readonly class CreateCheckInCommand implements Command
{
    private function __construct(public string $id, public string $userId, public \DateTimeImmutable $startDate, public ?\DateTimeImmutable $endDate)
    {
    }

    public static function create(string $id, string $userId, \DateTimeImmutable $startDate, ?\DateTimeImmutable $endDate): self
    {
        return new self(
            id: $id,
            userId: $userId,
            startDate: $startDate,
            endDate: $endDate
        );
    }
}

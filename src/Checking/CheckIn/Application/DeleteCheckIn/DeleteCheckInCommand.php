<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\DeleteCheckIn;

use App\Shared\Domain\Bus\Command\Command;

final readonly class DeleteCheckInCommand implements Command
{
    private function __construct(public string $id)
    {
    }

    public static function create(string $id): self
    {
        return new self(
            id: $id
        );
    }
}

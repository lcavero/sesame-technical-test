<?php declare(strict_types=1);

namespace App\Checking\User\Application\DeleteUser;

use App\Shared\Domain\Bus\Command\Command;

final readonly class DeleteUserCommand implements Command
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

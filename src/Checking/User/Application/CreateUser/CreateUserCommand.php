<?php declare(strict_types=1);

namespace App\Checking\User\Application\CreateUser;

use App\Shared\Domain\Bus\Command\Command;

final readonly class CreateUserCommand implements Command
{
    private function __construct(public string $id, public string $name, public string $email)
    {
    }

    public static function create(string $id, string $name, string $email): self
    {
        return new self(
            id: $id,
            name: $name,
            email: $email
        );
    }
}

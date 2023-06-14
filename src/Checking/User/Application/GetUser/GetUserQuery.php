<?php declare(strict_types=1);

namespace App\Checking\User\Application\GetUser;

use App\Shared\Domain\Bus\Query\Query;

final readonly class GetUserQuery implements Query
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

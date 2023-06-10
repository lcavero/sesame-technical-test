<?php declare(strict_types=1);

namespace App\Checking\User\Application\AllUsers;

use App\Shared\Domain\Bus\Query\Query;

final readonly class AllUsersQuery implements Query
{
    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }
}

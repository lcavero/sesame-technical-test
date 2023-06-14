<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\AllUserCheckIns;

use App\Shared\Domain\Bus\Query\Query;

final readonly class AllUserCheckInsQuery implements Query
{
    private function __construct(public string $userId)
    {
    }

    public static function create(string $userId): self
    {
        return new self($userId);
    }
}

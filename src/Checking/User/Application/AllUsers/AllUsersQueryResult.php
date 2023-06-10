<?php declare(strict_types=1);

namespace App\Checking\User\Application\AllUsers;

final readonly class AllUsersQueryResult
{
    private function __construct(public array $result)
    {
    }

    public static function fromArray(array $result): self
    {
        return new self(array_map(fn ($row) => [
            [
                'id' => $row['id'],
                'name' => $row['name'],
                'email' => $row['email']
            ]
        ], $result));
    }
}

<?php declare(strict_types=1);

namespace App\Checking\User\Application\GetUser;

final readonly class GetUserQueryResult
{
    private function __construct(public ?array $result)
    {
    }

    public static function fromArray(?array $result): self
    {
        if (null === $result) {
            return new self(null);
        }

        return new self([
            'id' => $result['id'],
            'name' => $result['name'],
            'email' => $result['email'],
            'createdAt' => $result['created_at'],
            'updatedAt' => $result['updated_at'],
            'deletedAt' => $result['deleted_at']
        ]);
    }
}

<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\GetCheckIn;

final readonly class GetCheckInQueryResult
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
            'startDate' => $result['start_date'],
            'endDate' => $result['end_date'],
            'createdAt' => $result['created_at'],
            'updatedAt' => $result['updated_at'],
            'deletedAt' => $result['deleted_at']
        ]);
    }
}

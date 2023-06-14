<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\AllUserCheckIns;

final readonly class AllUserCheckInsQueryResult
{
    private function __construct(public array $result)
    {
    }

    public static function fromArray(array $result): self
    {
        return new self(array_map(fn ($row) => [
            [
                'id' => $row['id'],
                'startDate' => $row['start_date'],
                'endDate' => $row['end_date']
            ]
        ], $result));
    }
}

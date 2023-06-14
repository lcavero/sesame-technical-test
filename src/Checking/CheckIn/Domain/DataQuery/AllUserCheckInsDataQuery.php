<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\DataQuery;

interface AllUserCheckInsDataQuery
{
    public function execute(string $userId): array;
}

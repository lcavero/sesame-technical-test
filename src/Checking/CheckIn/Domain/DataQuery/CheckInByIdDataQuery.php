<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Domain\DataQuery;

interface CheckInByIdDataQuery
{
    public function execute(string $id): ?array;
}

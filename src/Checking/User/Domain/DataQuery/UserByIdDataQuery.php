<?php declare(strict_types=1);

namespace App\Checking\User\Domain\DataQuery;

interface UserByIdDataQuery
{
    public function execute(string $id): ?array;
}

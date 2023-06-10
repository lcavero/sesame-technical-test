<?php declare(strict_types=1);

namespace App\Checking\User\Domain\DataQuery;

interface AllUsersDataQuery
{
    public function execute(): array;
}

<?php declare(strict_types=1);

namespace App\Checking\User\Application\AllUsers;

use App\Checking\User\Domain\DataQuery\AllUsersDataQuery;
use App\Shared\Domain\Bus\Query\QueryHandler;

final readonly class AllUsersQueryHandler implements QueryHandler
{
    public function __construct(private AllUsersDataQuery $dataQuery)
    {
    }

    public function __invoke(AllUsersQuery $query): AllUsersQueryResult
    {
        return AllUsersQueryResult::fromArray($this->dataQuery->execute());
    }
}

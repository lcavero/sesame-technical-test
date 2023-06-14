<?php declare(strict_types=1);

namespace App\Checking\User\Application\GetUser;

use App\Checking\User\Domain\DataQuery\UserByIdDataQuery;
use App\Shared\Domain\Bus\Query\QueryHandler;

final readonly class GetUserQueryHandler implements QueryHandler
{
    public function __construct(private UserByIdDataQuery $dataQuery)
    {
    }

    public function __invoke(GetUserQuery $query): GetUserQueryResult
    {
        return GetUserQueryResult::fromArray($this->dataQuery->execute($query->id));
    }
}

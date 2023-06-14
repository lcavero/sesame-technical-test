<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\AllUserCheckIns;

use App\Checking\CheckIn\Domain\DataQuery\AllUserCheckInsDataQuery;
use App\Shared\Domain\Bus\Query\QueryHandler;

final readonly class AllUserCheckInsQueryHandler implements QueryHandler
{
    public function __construct(private AllUserCheckInsDataQuery $dataQuery)
    {
    }

    public function __invoke(AllUserCheckInsQuery $query): AllUserCheckInsQueryResult
    {
        return AllUserCheckInsQueryResult::fromArray($this->dataQuery->execute($query->userId));
    }
}

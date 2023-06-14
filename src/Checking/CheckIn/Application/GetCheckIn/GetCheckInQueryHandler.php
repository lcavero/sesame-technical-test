<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Application\GetCheckIn;

use App\Checking\CheckIn\Domain\DataQuery\CheckInByIdDataQuery;
use App\Shared\Domain\Bus\Query\QueryHandler;

final readonly class GetCheckInQueryHandler implements QueryHandler
{
    public function __construct(private CheckInByIdDataQuery $dataQuery)
    {
    }

    public function __invoke(GetCheckInQuery $query): GetCheckInQueryResult
    {
        return GetCheckInQueryResult::fromArray($this->dataQuery->execute($query->id));
    }
}

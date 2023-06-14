<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns;

use App\Checking\CheckIn\Application\AllUserCheckIns\AllUserCheckInsQuery;
use App\Checking\CheckIn\Application\AllUserCheckIns\AllUserCheckInsQueryResult;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiGetResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class ListUserCheckInsApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private QueryBus $queryBus, private ValidatorInterface $validator)
    {
    }

    public function __invoke(string $userId): JsonResponse
    {
        $this->validateRequest(new ListUserCheckInsApiRouteParamsRequest($userId));
        $query = AllUserCheckInsQuery::create($userId);
        $result = $this->queryBus->handle($query);
        assert($result instanceof AllUserCheckInsQueryResult);
        return $this->jsonResponse(ApiGetResponse::create($result->result));
    }
}

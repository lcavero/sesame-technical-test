<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\ListUsers;

use App\Checking\User\Application\AllUsers\AllUsersQuery;
use App\Checking\User\Application\AllUsers\AllUsersQueryResult;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiGetResponse;
use Symfony\Component\HttpFoundation\JsonResponse;


final readonly class ListUsersApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private QueryBus $queryBus)
    {
    }

    public function __invoke(): JsonResponse
    {
        $query = AllUsersQuery::create();
        $result = $this->queryBus->handle($query);
        assert($result instanceof AllUsersQueryResult);
        return $this->jsonResponse(ApiGetResponse::create($result->result));
    }
}

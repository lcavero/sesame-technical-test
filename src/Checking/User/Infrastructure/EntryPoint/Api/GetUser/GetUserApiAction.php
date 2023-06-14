<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\GetUser;

use App\Checking\User\Application\GetUser\GetUserQuery;
use App\Checking\User\Application\GetUser\GetUserQueryResult;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiGetResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class GetUserApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private QueryBus $queryBus, private ValidatorInterface $validator)
    {
    }

    #[OA\Get]
    public function __invoke(string $id): JsonResponse
    {
        $this->validateRequest(new GetUserApiRouteParamsRequest($id));

        $query = GetUserQuery::create(
            id: $id,
        );
        $result = $this->queryBus->handle($query);
        assert($result instanceof GetUserQueryResult);
        return $this->jsonResponse(ApiGetResponse::create($result->result));
    }
}

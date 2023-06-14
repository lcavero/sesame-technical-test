<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\GetCheckIn;

use App\Checking\CheckIn\Application\GetCheckIn\GetCheckInQuery;
use App\Checking\CheckIn\Application\GetCheckIn\GetCheckInQueryResult;
use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiGetResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class GetCheckInApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private QueryBus $queryBus, private ValidatorInterface $validator)
    {
    }

    #[OA\Get]
    public function __invoke(string $id): JsonResponse
    {
        $this->validateRequest(new GetCheckInApiRouteParamsRequest($id));

        $query = GetCheckInQuery::create(
            id: $id,
        );
        $result = $this->queryBus->handle($query);
        assert($result instanceof GetCheckInQueryResult);
        return $this->jsonResponse(ApiGetResponse::create($result->result));
    }
}

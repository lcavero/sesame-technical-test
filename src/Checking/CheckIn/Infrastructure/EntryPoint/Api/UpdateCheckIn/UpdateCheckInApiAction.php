<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\UpdateCheckIn;

use App\Checking\CheckIn\Application\UpdateCheckIn\UpdateCheckInCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiUpdatedResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class UpdateCheckInApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus, private ValidatorInterface $validator)
    {
    }

    #[OA\Put(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: UpdateCheckInApiRequest::class))))]
    public function __invoke(string $id, #[MapRequestPayload] UpdateCheckInApiRequest $request): JsonResponse
    {
        $this->validateRequest(new UpdateCheckInApiRouteParamsRequest($id));

        $command = UpdateCheckInCommand::create(
            id: $id,
            userId: $request->userId,
            startDate: \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->startDate),
            endDate: null !== $request->endDate ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->endDate) : null
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiUpdatedResponse::create());
    }
}

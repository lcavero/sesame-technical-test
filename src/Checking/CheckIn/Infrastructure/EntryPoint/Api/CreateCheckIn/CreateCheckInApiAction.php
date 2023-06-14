<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\CreateCheckIn;

use App\Checking\CheckIn\Application\CreateCheckIn\CreateCheckInCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiCreatedResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


final readonly class CreateCheckInApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus)
    {
    }

    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: CreateCheckInApiRequest::class))))]
    public function __invoke(#[MapRequestPayload] CreateCheckInApiRequest $request): JsonResponse
    {
        $command = CreateCheckInCommand::create(
            id: $request->id,
            userId: $request->userId,
            startDate: \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->startDate),
            endDate: null !== $request->endDate ? \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $request->endDate) : null
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiCreatedResponse::create());
    }
}

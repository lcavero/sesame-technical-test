<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\DeleteCheckIn;

use App\Checking\CheckIn\Application\DeleteCheckIn\DeleteCheckInCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiDeletedResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class DeleteCheckInApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus, private ValidatorInterface $validator)
    {
    }

    #[OA\Delete]
    public function __invoke(string $id): JsonResponse
    {
        $this->validateRequest(new DeleteCheckInApiRouteParamsRequest($id));

        $command = DeleteCheckInCommand::create(
            id: $id,
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiDeletedResponse::create());
    }
}

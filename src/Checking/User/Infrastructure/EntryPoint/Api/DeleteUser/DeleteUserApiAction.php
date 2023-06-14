<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\DeleteUser;

use App\Checking\User\Application\DeleteUser\DeleteUserCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiDeletedResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;


final readonly class DeleteUserApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus, private ValidatorInterface $validator)
    {
    }

    #[OA\Delete]
    public function __invoke(string $id): JsonResponse
    {
        $this->validateRequest(new DeleteUserApiRouteParamsRequest($id));

        $command = DeleteUserCommand::create(
            id: $id,
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiDeletedResponse::create());
    }
}

<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\CreateUser;

use App\Checking\User\Application\CreateUser\CreateUserCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiCreatedResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;

final readonly class CreateUserApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus)
    {
    }

    public function __invoke(#[MapRequestPayload] CreateUserApiRequest $request): JsonResponse
    {
        $command = CreateUserCommand::create(
            id: $request->id,
            name: $request->name,
            email: $request->email
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiCreatedResponse::create());
    }
}

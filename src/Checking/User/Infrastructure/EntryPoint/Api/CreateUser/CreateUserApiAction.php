<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\CreateUser;

use App\Checking\User\Application\CreateUser\CreateUserCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiCreatedResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use OpenApi\Attributes as OA;


final readonly class CreateUserApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus)
    {
    }

    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: CreateUserApiRequest::class))))]
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

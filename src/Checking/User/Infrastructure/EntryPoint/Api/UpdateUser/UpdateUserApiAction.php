<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

use App\Checking\User\Application\UpdateUser\UpdateUserCommand;
use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Infrastructure\EntryPoint\Api\ApiController;
use App\Shared\Infrastructure\EntryPoint\Api\ApiControllerTrait;
use App\Shared\Infrastructure\EntryPoint\Api\Response\ApiUpdatedResponse;
use Nelmio\ApiDocBundle\Annotation\Model;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;


final readonly class UpdateUserApiAction implements ApiController
{
    use ApiControllerTrait;

    public function __construct(private CommandBus $commandBus)
    {
    }

    #[OA\Post(requestBody: new OA\RequestBody(content: new OA\JsonContent(ref: new Model(type: UpdateUserApiRequest::class))))]
    public function __invoke(#[MapRequestPayload] UpdateUserApiRequest $request): JsonResponse
    {
        $command = UpdateUserCommand::create(
            id: $request->id,
            name: $request->name,
            email: $request->email
        );
        $this->commandBus->dispatch($command);
        return $this->jsonResponse(ApiUpdatedResponse::create());
    }
}

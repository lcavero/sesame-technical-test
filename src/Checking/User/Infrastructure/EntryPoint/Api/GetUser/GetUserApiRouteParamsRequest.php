<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\GetUser;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class GetUserApiRouteParamsRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: UserId::UUID_PATTERN),
            new EntityExists(entity: User::class, field: 'id'),
        ])]
        public string $id,
    ) {
    }
}

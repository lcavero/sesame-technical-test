<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use App\Shared\Infrastructure\Validation\Constraints\EntityNotExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserApiRouteParamsRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: UserId::UUID_PATTERN),
            new EntityExists(entity: User::class, field: 'id'),
        ])]
        public string $id,

        #[Assert\Sequentially([
            new EntityNotExists(entity: User::class, field: 'email.value', excludedEntityField: 'id'),
        ])]
        public string $email,
    ) {
    }
}

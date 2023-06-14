<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\ListUserCheckIns;

use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\User\Domain\Aggregate\User;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class ListUserCheckInsApiRouteParamsRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: CheckInUserId::UUID_PATTERN),
            new EntityExists(entity: User::class, field: 'id'),
        ])]
        public string $userId,
    ) {
    }
}

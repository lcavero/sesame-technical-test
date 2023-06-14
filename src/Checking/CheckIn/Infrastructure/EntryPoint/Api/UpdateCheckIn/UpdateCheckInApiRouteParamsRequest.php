<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\UpdateCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateCheckInApiRouteParamsRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: CheckInId::UUID_PATTERN),
            new EntityExists(entity: CheckIn::class, field: 'id'),
        ])]
        public string $id,
    ) {
    }
}

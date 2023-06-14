<?php declare(strict_types=1);

namespace App\Checking\CheckIn\Infrastructure\EntryPoint\Api\CreateCheckIn;

use App\Checking\CheckIn\Domain\Aggregate\CheckIn;
use App\Checking\CheckIn\Domain\Aggregate\CheckInId;
use App\Checking\CheckIn\Domain\Aggregate\CheckInUserId;
use App\Checking\User\Domain\Aggregate\User;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use App\Shared\Infrastructure\Validation\Constraints\EntityNotExists;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final readonly class CreateCheckInApiRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: CheckInId::UUID_PATTERN),
            new EntityNotExists(entity: CheckIn::class, field: 'id'),
        ])]
        public string $id,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: CheckInUserId::UUID_PATTERN),
            new EntityExists(entity: User::class, field: 'id'),
        ])]
        public string $userId,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\DateTime(),
        ])]
        public string $startDate,

        #[Assert\AtLeastOneOf([
                new Assert\IsNull(),
                new Assert\Sequentially([
                    new Assert\NotBlank(),
                    new Assert\Type('string'),
                    new Assert\DateTime(),
                    new Assert\Callback([CreateCheckInApiRequest::class, 'validateEndDate']),
                ])
            ]
        )]
        public ?string $endDate,
    ) {
    }

    public static function validateEndDate(mixed $endDate, ExecutionContextInterface $context): void
    {
        $startDate = $context->getObject()?->startDate;
        if ('' !== $startDate && null !== $startDate) {
            $startDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $startDate);
            if (false !== $startDateTime) {
                $endDateTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $endDate);
                if ($endDateTime < $startDateTime) {
                    $context->buildViolation('endDate should be greater than startDate')
                        ->atPath('endDate')
                        ->addViolation()
                    ;
                }
            }
        }
    }
}

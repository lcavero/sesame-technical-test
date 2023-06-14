<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\UpdateUser;

use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserName;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class UpdateUserApiRequest
{
    public function __construct(
        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(min: UserName::MIN_LENGTH, max: UserName::MAX_LENGTH),
        ])]
        public string $name,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: UserEmail::EMAIL_PATTERN),
        ])]
        public string $email,
    ) {
    }
}

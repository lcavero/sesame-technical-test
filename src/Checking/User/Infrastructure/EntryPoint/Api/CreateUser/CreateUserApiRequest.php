<?php declare(strict_types=1);

namespace App\Checking\User\Infrastructure\EntryPoint\Api\CreateUser;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Shared\Infrastructure\Validation\Constraints\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateUserApiRequest
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
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Length(min: UserName::MIN_LENGTH, max: UserName::MAX_LENGTH),
        ])]
        public string $name,

        #[Assert\Sequentially([
            new Assert\NotBlank(),
            new Assert\Type('string'),
            new Assert\Regex(pattern: UserEmail::EMAIL_PATTERN),
            new EntityExists(entity: User::class, field: 'email'),
        ])]
        public string $email,
    ) {
    }
}

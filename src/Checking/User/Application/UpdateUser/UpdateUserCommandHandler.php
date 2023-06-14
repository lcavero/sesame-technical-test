<?php declare(strict_types=1);

namespace App\Checking\User\Application\UpdateUser;

use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Exception\UserAlreadyExistsException;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class UpdateUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(UpdateUserCommand $command): void
    {
        $user = $this->userRepository->findOneByIdOrFail(UserId::fromString($command->id));

        $userWithSameEmail = $this->userRepository->findOneByEmail(UserEmail::fromString($command->email));
        if (null !== $userWithSameEmail && $userWithSameEmail->id()->value !== $command->id) {
            throw UserAlreadyExistsException::create(sprintf('An user with email "%s" already exists.', $command->email));
        }

        $user->update(
            name: UserName::fromString($command->name),
            email: UserEmail::fromString($command->email)
        );

        $this->userRepository->save($user);
    }
}

<?php declare(strict_types=1);

namespace App\Checking\User\Application\CreateUser;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Exception\UserAlreadyExistsException;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class CreateUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(CreateUserCommand $command): void
    {
        if (null !== $this->userRepository->findOneById(UserId::fromString($command->id))) {
            throw UserAlreadyExistsException::create(sprintf('An user with id "%s" already exists.', $command->id));
        }

        if (null !== $this->userRepository->findOneByEmail(UserEmail::fromString($command->email))) {
            throw UserAlreadyExistsException::create(sprintf('An user with email "%s" already exists.', $command->email));
        }

        $user = User::create(
            id: UserId::fromString($command->id),
            name: UserName::fromString($command->name),
            email: UserEmail::fromString($command->email)
        );
        $this->userRepository->save($user);
    }
}

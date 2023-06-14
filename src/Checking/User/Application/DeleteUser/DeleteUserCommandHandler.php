<?php declare(strict_types=1);

namespace App\Checking\User\Application\DeleteUser;

use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Repository\UserRepository;
use App\Shared\Domain\Bus\Command\CommandHandler;

final readonly class DeleteUserCommandHandler implements CommandHandler
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function __invoke(DeleteUserCommand $command): void
    {
        $user = $this->userRepository->findOneByIdOrFail(UserId::fromString($command->id));

        $user->delete();

        $this->userRepository->save($user);
    }
}

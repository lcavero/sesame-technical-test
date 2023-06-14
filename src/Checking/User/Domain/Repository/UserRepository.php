<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Repository;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;

interface UserRepository
{
    public function findOneById(UserId $id): ?User;
    public function findOneByIdOrFail(UserId $id): User;

    public function findOneByEmail(UserEmail $email): ?User;
    public function save(User $user): void;
}

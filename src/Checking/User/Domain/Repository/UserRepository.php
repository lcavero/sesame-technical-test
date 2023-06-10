<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Repository;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserId;

interface UserRepository
{
    public function findOneByIdOrFail(UserId $id): User;
    public function save(User $user): void;
}

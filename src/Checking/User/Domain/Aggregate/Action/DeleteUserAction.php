<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate\Action;

use App\Checking\User\Domain\Aggregate\UserDeletedAt;

trait DeleteUserAction
{
    public function delete(): void
    {
        $this->deletedAt = UserDeletedAt::fromDateTimeImmutable(new \DateTimeImmutable());
    }
}

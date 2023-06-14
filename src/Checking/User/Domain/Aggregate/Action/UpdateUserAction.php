<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate\Action;

use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;

trait UpdateUserAction
{
    public function update(
        UserName $name,
        UserEmail $email,
    ): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->updatedAt = UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable());
    }
}

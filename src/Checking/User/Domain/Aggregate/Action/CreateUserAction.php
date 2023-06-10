<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate\Action;

use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;

trait CreateUserAction
{
    public static function create(
        UserId $id,
        UserName $name,
        UserEmail $email,
    ): self
    {
        return new self(
            $id->value,
            $name,
            $email,
            UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            UserDeletedAt::fromNull()
        );
    }
}

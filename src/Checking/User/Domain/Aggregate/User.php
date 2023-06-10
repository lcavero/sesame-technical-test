<?php declare(strict_types=1);

namespace App\Checking\User\Domain\Aggregate;

use App\Checking\User\Domain\Aggregate\Action\CreateUserAction;

class User
{
    use CreateUserAction;

    private function __construct(
        private string $id,
        private UserName $name,
        private UserEmail $email,
        private UserCreatedAt $createdAt,
        private UserUpdatedAt $updatedAt,
        private UserDeletedAt $deletedAt
    ) {
    }

    public static function fromValues(
        UserId $id,
        UserName $name,
        UserEmail $email,
        UserCreatedAt $createdAt,
        UserUpdatedAt $updatedAt,
        UserDeletedAt $deletedAt
    ): self
    {
        return new self(
            $id->value,
            $name,
            $email,
            $createdAt,
            $updatedAt,
            $deletedAt
        );
    }

    public function id(): UserId
    {
        return UserId::fromString($this->id);
    }

    public function name(): UserName
    {
        return $this->name;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function createdAt(): UserCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): UserUpdatedAt
    {
        return $this->updatedAt;
    }

    public function deletedAt(): UserDeletedAt
    {
        return $this->deletedAt;
    }
}

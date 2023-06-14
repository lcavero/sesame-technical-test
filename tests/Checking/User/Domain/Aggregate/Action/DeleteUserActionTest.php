<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Domain\Aggregate\Action;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use PHPUnit\Framework\TestCase;

final class DeleteUserActionTest extends TestCase
{

    /** @dataProvider deleteSuccessfullyDataProvider */
    public function testDeleteSuccessfully(string $id): void
    {
        $user = User::fromValues(
            id: UserId::fromString($id),
            name: UserName::fromString('Test'),
            email: UserEmail::fromString('test@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        );

        $user->delete();

        self::assertNotNull($user->deletedAt()->value);
    }

    public function deleteSuccessfullyDataProvider(): array
    {
        return [
            'Standard fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
            ],
        ];
    }
}

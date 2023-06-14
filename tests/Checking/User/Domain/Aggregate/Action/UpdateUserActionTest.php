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
use function PHPUnit\Framework\assertSame;

final class UpdateUserActionTest extends TestCase
{

    /** @dataProvider updateSuccessfullyDataProvider */
    public function testUpdateSuccessfully(string $id, string $name, string $email): void
    {
        $user = User::fromValues(
            id: UserId::fromString($id),
            name: UserName::fromString('Test'),
            email: UserEmail::fromString('test@test.com'),
            createdAt: UserCreatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            updatedAt: UserUpdatedAt::fromDateTimeImmutable(new \DateTimeImmutable()),
            deletedAt: UserDeletedAt::fromNull()
        );

        $oldUpdatedAt = $user->updatedAt();

        $user->update(
            name: UserName::fromString($name),
            email: UserEmail::fromString($email),
        );

        assertSame($id, $user->id()->value);
        assertSame($name, $user->name()->value);
        assertSame($email, $user->email()->value);
        self::assertGreaterThan($oldUpdatedAt->value, $user->updatedAt()->value);
    }

    public function updateSuccessfullyDataProvider(): array
    {
        return [
            'Standard fields' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'name' => 'Paco',
                'email' => 'paco@gmail.com',
            ],
        ];
    }
}

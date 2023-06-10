<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Domain\Aggregate\Action;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

final class CreateUserActionTest extends TestCase
{

    /** @dataProvider createSuccessfullyDataProvider */
    public function testCreateSuccessfully(string $id, string $name, string $email): void
    {
        $user = User::create(
            id: UserId::fromString($id),
            name: UserName::fromString($name),
            email: UserEmail::fromString($email),
        );

        assertSame($id, $user->id()->value);
        assertSame($name, $user->name()->value);
        assertSame($email, $user->email()->value);
        self::assertNotNull($user->createdAt()->value);
        self::assertNotNull($user->updatedAt()->value);
        self::assertNull($user->deletedAt()->value);
    }

    public function createSuccessfullyDataProvider(): array
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

<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Domain\Aggregate;

use App\Checking\User\Domain\Aggregate\User;
use App\Checking\User\Domain\Aggregate\UserCreatedAt;
use App\Checking\User\Domain\Aggregate\UserDeletedAt;
use App\Checking\User\Domain\Aggregate\UserEmail;
use App\Checking\User\Domain\Aggregate\UserId;
use App\Checking\User\Domain\Aggregate\UserName;
use App\Checking\User\Domain\Aggregate\UserUpdatedAt;
use PHPUnit\Framework\TestCase;
use function PHPUnit\Framework\assertSame;

final class UserTest extends TestCase
{
    /** @dataProvider fromValuesSuccessfullyDataProvider */
    public function testFromValuesSuccessfully(string $id, string $name, string $email, string $createdAt,
                                           string $updatedAt, ?string $deletedAt): void
    {
        $user = User::fromValues(
            id: UserId::fromString($id),
            name: UserName::fromString($name),
            email: UserEmail::fromString($email),
            createdAt: UserCreatedAt::fromATOM($createdAt),
            updatedAt: UserUpdatedAt::fromATOM($updatedAt),
            deletedAt: UserDeletedAt::fromATOM($deletedAt)
        );

        assertSame($id, $user->id()->value);
        assertSame($name, $user->name()->value);
        assertSame($email, $user->email()->value);
        assertSame($createdAt, $user->createdAt()->toATOM());
        assertSame($updatedAt, $user->updatedAt()->toATOM());
        assertSame($deletedAt, $user->deletedAt()->toATOM());
    }

    public function fromValuesSuccessfullyDataProvider(): array
    {
        return [
            'With deletedAt field' => [
                'id' => '5c4130c3-ae89-429c-9474-9eb49e329b68',
                'name' => 'Luis',
                'email' => 'lcv@gmail.com',
                'createdAt' => '2020-01-03T02:30:00+01:00',
                'updatedAt' => '2021-01-03T02:30:00+01:00',
                'deletedAt' => '2022-01-03T02:30:00+01:00'
            ],
            'Without deletedAt field' => [
                'id' => '8e9cc290-5b17-47fd-9204-9f239c3e7362',
                'name' => 'Ramón',
                'email' => 'errej22@gmail.com',
                'createdAt' => '2019-01-03T02:35:00+01:00',
                'updatedAt' => '2021-04-03T02:40:00+01:00',
                'deletedAt' => null
            ],
        ];
    }
}

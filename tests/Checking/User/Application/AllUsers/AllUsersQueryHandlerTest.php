<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Application\AllUsers;

use App\Checking\User\Application\AllUsers\AllUsersQuery;
use App\Checking\User\Application\AllUsers\AllUsersQueryHandler;
use App\Checking\User\Application\AllUsers\AllUsersQueryResult;
use App\Checking\User\Domain\DataQuery\AllUsersDataQuery;
use PHPUnit\Framework\TestCase;

final class AllUsersQueryHandlerTest extends TestCase
{
    public function testAllUsersQueryHandlerSuccessfully(): void
    {
        $dataQueryMock = $this->createMock(AllUsersDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(
                [
                    [
                        'id' => 'ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38',
                        'name' => 'Luis',
                        'email' => 'lcv@test.com'
                    ],
                    [
                        'id' => '01f39657-a3bd-4037-8ae6-d90a868d8f44',
                        'name' => 'Test',
                        'email' => 'test@test.com'
                    ],
                ]
            )
        ;

        $query = AllUsersQuery::create();
        $queryHandler = new AllUsersQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(AllUsersQueryResult::class, $result);
        self::assertCount(2, $result->result);
    }
}

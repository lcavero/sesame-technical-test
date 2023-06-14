<?php declare(strict_types=1);

namespace App\Tests\Checking\User\Application\GetUser;

use App\Checking\User\Application\GetUser\GetUserQuery;
use App\Checking\User\Application\GetUser\GetUserQueryHandler;
use App\Checking\User\Application\GetUser\GetUserQueryResult;
use App\Checking\User\Domain\DataQuery\UserByIdDataQuery;
use PHPUnit\Framework\TestCase;

final class GetUserQueryHandlerTest extends TestCase
{
    public function testQueryHandlerSuccessfully(): void
    {
        $dataQueryMock = $this->createMock(UserByIdDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(
                [
                    'id' => 'ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38',
                    'name' => 'Luis',
                    'email' => 'lcv@test.com',
                    'created_at' => '2020-10-10 00:00:00',
                    'updated_at' => '2020-10-10 00:00:00',
                    'deleted_at' => null
                ],
            )
        ;

        $query = GetUserQuery::create('ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38');
        $queryHandler = new GetUserQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(GetUserQueryResult::class, $result);
        self::assertNotNull($result->result);
    }

    public function testQueryHandlerNotFound(): void
    {
        $dataQueryMock = $this->createMock(UserByIdDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(null)
        ;

        $query = GetUserQuery::create('ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38');
        $queryHandler = new GetUserQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(GetUserQueryResult::class, $result);
        self::assertNull($result->result);
    }
}

<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Application\GetCheckIn;

use App\Checking\CheckIn\Application\GetCheckIn\GetCheckInQueryHandler;
use App\Checking\CheckIn\Application\GetCheckIn\GetCheckInQuery;
use App\Checking\CheckIn\Application\GetCheckIn\GetCheckInQueryResult;
use App\Checking\CheckIn\Domain\DataQuery\CheckInByIdDataQuery;
use PHPUnit\Framework\TestCase;

final class GetCheckInQueryHandlerTest extends TestCase
{

    public function testQueryHandlerSuccessfully(): void
    {
        $dataQueryMock = $this->createMock(CheckInByIdDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(
                [
                    'id' => 'ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38',
                    'start_date' => '2020-10-10 00:00:00',
                    'end_date' => '2020-10-10 00:00:00',
                    'created_at' => '2020-10-10 00:00:00',
                    'updated_at' => '2020-10-10 00:00:00',
                    'deleted_at' => null
                ],
            )
        ;

        $query = GetCheckInQuery::create('ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38');
        $queryHandler = new GetCheckInQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(GetCheckInQueryResult::class, $result);
        self::assertNotNull($result->result);
    }

    public function testQueryHandlerNotFound(): void
    {
        $dataQueryMock = $this->createMock(CheckInByIdDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(null)
        ;

        $query = GetCheckInQuery::create('ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38');
        $queryHandler = new GetCheckInQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(GetCheckInQueryResult::class, $result);
        self::assertNull($result->result);
    }
}

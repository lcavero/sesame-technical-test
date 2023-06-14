<?php declare(strict_types=1);

namespace App\Tests\Checking\CheckIn\Application\AllUserCheckIns;

use App\Checking\CheckIn\Application\AllUserCheckIns\AllUserCheckInsQuery;
use App\Checking\CheckIn\Application\AllUserCheckIns\AllUserCheckInsQueryHandler;
use App\Checking\CheckIn\Application\AllUserCheckIns\AllUserCheckInsQueryResult;
use App\Checking\CheckIn\Domain\DataQuery\AllUserCheckInsDataQuery;
use PHPUnit\Framework\TestCase;

final class AllUserCheckInsQueryHandlerTest extends TestCase
{

    public function testAllUserCheckInsQueryHandlerSuccessfully(): void
    {
        $dataQueryMock = $this->createMock(AllUserCheckInsDataQuery::class);
        $dataQueryMock->expects($this->once())
            ->method('execute')
            ->willReturn(
                [
                    [
                        'id' => 'ebb9c2c9-f9ab-4ab8-a715-88845a9a9b38',
                        'start_date' => '2020-10-10 00:00:00',
                        'end_date' => '2020-10-10 00:00:00',
                    ],
                    [
                        'id' => '01f39657-a3bd-4037-8ae6-d90a868d8f44',
                        'start_date' => '2020-10-10 00:00:00',
                        'end_date' => null
                    ],
                ]
            )
        ;

        $query = AllUserCheckInsQuery::create('ebb9c2c9-f9ab-4ab8-a715-88845a9a9b39');
        $queryHandler = new AllUserCheckInsQueryHandler($dataQueryMock);
        $result = $queryHandler->__invoke($query);
        self::assertInstanceOf(AllUserCheckInsQueryResult::class, $result);
        self::assertCount(2, $result->result);
    }
}

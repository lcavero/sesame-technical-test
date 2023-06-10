<?php declare(strict_types=1);

namespace App\Tests\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\Command;
use App\Shared\Infrastructure\Bus\Command\MessengerCommandBus;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final class MessengerCommandBusTest extends TestCase
{
    public function testDispatchSuccessfully(): void
    {
        $commandMock = $this->createMock(Command::class);
        $busMock = $this->createMock(MessageBusInterface::class);
        $busMock->expects($this->once())
            ->method('dispatch')
            ->with($this->identicalTo($commandMock))
            ->willReturn(new Envelope(new \stdClass()))
        ;

        $commandBus = new MessengerCommandBus($busMock);
        $commandBus->dispatch($commandMock);
    }

    public function testDispatchAsyncSuccessfully(): void
    {
        $commandMock = $this->createMock(Command::class);
        $busMock = $this->createMock(MessageBusInterface::class);
        $busMock->expects($this->once())
            ->method('dispatch')
            ->with($this->identicalTo($commandMock), self::callback(function ($stamps): bool {
                self::assertIsArray($stamps);
                self::assertCount(1, $stamps);
                $stamp = array_shift($stamps);
                self::assertInstanceOf(TransportNamesStamp::class, $stamp);
                self::assertSame('async', $stamp->getTransportNames()[0]);

                return true;
            }))
            ->willReturn(new Envelope(new \stdClass()))
        ;

        $commandBus = new MessengerCommandBus($busMock);
        $commandBus->dispatchAsync($commandMock);
    }
}

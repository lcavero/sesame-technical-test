<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Command;

use App\Shared\Domain\Bus\Command\CommandBus;
use App\Shared\Domain\Bus\Command\Command;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\TransportNamesStamp;

final readonly class MessengerCommandBus implements CommandBus
{
    public function __construct(private MessageBusInterface $commandBus)
    {
    }

    public function dispatch(Command $command): void
    {
        $this->commandBus->dispatch($command);
    }

    public function dispatchAsync(Command $command): void
    {
        $this->commandBus->dispatch($command, [new TransportNamesStamp(['async'])]);
    }
}

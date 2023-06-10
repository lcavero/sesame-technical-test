<?php declare(strict_types=1);

namespace App\Shared\Domain\Bus\Command;

interface CommandBus
{
    public function dispatch(Command $command): void;
    public function dispatchAsync(Command $command): void;
}

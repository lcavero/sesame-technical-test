<?php declare(strict_types=1);

namespace App\Shared\Infrastructure\Bus\Query;

use App\Shared\Domain\Bus\Query\QueryBus;
use App\Shared\Domain\Bus\Query\Query;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

final class MessengerQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }

    public function __construct(private readonly MessageBusInterface $queryBus)
    {
        $this->messageBus = $this->queryBus;
    }

    public function handle(Query $query): mixed
    {
        try {
            return $this->handleQuery($query);
        } catch (HandlerFailedException $e) {
            while ($e instanceof HandlerFailedException) {
                if (null !== $e->getPrevious()) {
                    $e = $e->getPrevious();
                }
            }
            throw $e;
        }
    }
}

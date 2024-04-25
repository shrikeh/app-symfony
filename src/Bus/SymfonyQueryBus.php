<?php

declare(strict_types=1);

namespace RpHaven\App\Bus;

use RpHaven\App\Bus\Exception\ErrorHandlingQuery;
use RpHaven\App\Query;
use RpHaven\App\Query\QueryBus;
use RpHaven\App\Query\QueryBus\Exception\QueryBusException;
use RpHaven\App\Result;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }
    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @param Query $query
     * @return Result
     * @throws QueryBusException
     */
    public function handle(Query $query): Result
    {
        try {
            return $this->handleQuery($query);
        } catch (Throwable $exc) {
            throw new ErrorHandlingQuery($query, $exc);
        }
    }
}

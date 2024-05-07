<?php

declare(strict_types=1);

namespace RpHaven\App\Bus;

use RpHaven\App\Bus\Exception\ErrorHandlingQuery;
use RpHaven\App\Message\Query;
use RpHaven\App\Message\Result;
use RpHaven\App\Query\QueryBus;
use RpHaven\App\Query\QueryBus\Exception\QueryBusException;
use Symfony\Component\Messenger\HandleTrait;
use Throwable;

final readonly class SymfonyQueryBus implements QueryBus
{
    use HandleTrait {
        handle as handleQuery;
    }
    public function __construct(private CorrelatingMessageBus $queryBus)
    {
    }

    /**
     * @param Query $query
     * @return Result
     * @throws QueryBusException
     */
    public function handle(Query $query): Result
    {
        try {
            return $this->queryBus->message($query);
        } catch (Throwable $exc) {
            throw new ErrorHandlingQuery($query, $exc);
        }
    }
}

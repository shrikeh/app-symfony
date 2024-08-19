<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus;

use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingQuery;
use Shrikeh\SymfonyApp\Bus\Exception\QueryMustReturnAResult;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;
use Throwable;

final readonly class SymfonyQueryBus implements QueryBus
{
    public function __construct(private MessageBus $queryBus)
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
            $result = $this->queryBus->message($query);
        } catch (Throwable $exc) {
            throw new ErrorHandlingQuery($query, $exc);
        }
        /** @psalm-assert Result $result */
        if (!$result instanceof Result) {
            throw new QueryMustReturnAResult($query);
        }

        return $result;
    }
}

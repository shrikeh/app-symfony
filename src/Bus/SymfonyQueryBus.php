<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus;

use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingQuery;
use Shrikeh\SymfonyApp\Bus\Exception\QueryMustReturnAResult;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;
use Shrikeh\SymfonyApp\Bus\Traits\ResultAssertion;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyQueryBus implements QueryBus
{
    use ResultAssertion;
    use HandleTrait {
        handle as private handleQuery;
    }

    /**
     * @param MessageBusInterface $queryBus
     */
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
            $result = $this->handleQuery($query);
        } catch (Throwable $exc) {
            throw new ErrorHandlingQuery($query, $exc);
        }

        $this->assertResult($query, $result);

        return $result;
    }
}

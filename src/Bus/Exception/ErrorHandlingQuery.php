<?php

declare(strict_types=1);

namespace RpHaven\App\Bus\Exception;

use RpHaven\App\Query;
use RpHaven\App\Query\QueryBus\Exception\QueryBusException;
use RuntimeException;
use Throwable;

final class ErrorHandlingQuery extends RuntimeException implements QueryBusException, SymfonyMessageBusException
{
    public function __construct(public readonly Query $query, Throwable $previous)
    {
        parent::__construct(
            message: sprintf('Error handling query %s: %s', get_class($this->query), $previous->getMessage()),
            previous: $previous,
        );
    }
}
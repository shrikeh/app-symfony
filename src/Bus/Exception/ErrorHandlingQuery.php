<?php

declare(strict_types=1);

namespace Shrikeh\App\Bus\Exception;

use Shrikeh\App\Message\Query;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;
use RuntimeException;
use Throwable;

final class ErrorHandlingQuery extends RuntimeException implements QueryBusException, SymfonyMessageBusException
{
    public const string MSG_FORMAT = 'Error handling query %s: %s';
    public function __construct(public readonly Query $query, Throwable $previous)
    {
        parent::__construct(
            message: sprintf(self::MSG_FORMAT, get_class($this->query), $previous->getMessage()),
            previous: $previous,
        );
    }
}

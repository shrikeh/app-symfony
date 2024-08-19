<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Exception;

use Shrikeh\App\Message\Query;
use Shrikeh\App\Query\QueryBus\Exception\QueryBusException;
use RuntimeException;
use Shrikeh\SymfonyApp\Exception\ExceptionMessage;
use Throwable;

final class ErrorHandlingQuery extends RuntimeException implements QueryBusException, SymfonyMessageBusException
{
    public const ExceptionMessage MSG = ExceptionMessage::ERROR_HANDLING_QUERY;
    public function __construct(public readonly Query $query, Throwable $previous)
    {
        parent::__construct(
            message: self::MSG->message(get_class($this->query), $previous->getMessage()),
            previous: $previous,
        );
    }
}

<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator\CorrelatedQuery\Exception;

use RuntimeException;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator\Exception\DecoratorExceptionInterface;
use Shrikeh\SymfonyApp\Http\Exception\ExceptionMessage;

final class QueryNotCorrelatable extends RuntimeException implements DecoratorExceptionInterface
{
    public const ExceptionMessage MSG = ExceptionMessage::UNCORRELATABLE_QUERY;

    public function __construct(public readonly Query $query)
    {
        parent::__construct(self::MSG->message(
            get_class($this->query),
            Correlated::class,
        ));
    }
}

<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Exception;

use LogicException;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Exception\ExceptionMessage;

final class QueryMustReturnAResult extends LogicException implements SymfonyMessageBusException
{
    public const ExceptionMessage MSG = ExceptionMessage::QUERY_NO_RESULT;

    public function __construct(public readonly Query $query)
    {
        parent::__construct(self::MSG->message(get_class($this->query)));
    }
}

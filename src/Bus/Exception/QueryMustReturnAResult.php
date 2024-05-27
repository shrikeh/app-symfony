<?php

declare(strict_types=1);

namespace Shrikeh\App\Bus\Exception;

use LogicException;
use Shrikeh\App\Message\Query;

final class QueryMustReturnAResult extends LogicException implements SymfonyMessageBusException
{
    public const string MSG = 'Query %s failed to return a valid result';

    public function __construct(public readonly Query $query)
    {
        parent::__construct(sprintf(self::MSG, get_class($this->query)));
    }
}

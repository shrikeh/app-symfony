<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Logger\Exception;

use InvalidArgumentException;
use Shrikeh\App\Log\Exception\LogException;

final class NoContextsPassed extends InvalidArgumentException implements LogException
{
    public const string MSG = 'You must pass at least one Context to the app logger';

    public function __construct()
    {
        parent::__construct(self::MSG);
    }
}

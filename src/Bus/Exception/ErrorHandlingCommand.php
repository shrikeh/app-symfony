<?php

declare(strict_types=1);

namespace RpHaven\App\Bus\Exception;

use RpHaven\App\Command;
use RpHaven\App\Command\CommandBus\Exception\CommandBusException;
use RuntimeException;
use Throwable;

final class ErrorHandlingCommand extends RuntimeException implements CommandBusException, SymfonyMessageBusException
{
    public const MSG_FORMAT = 'Error handling command %s: %s';

    public function __construct(public readonly Command $command, Throwable $previous)
    {
        parent::__construct(
            message: sprintf(self::MSG_FORMAT, get_class($this->command), $previous->getMessage()),
            previous: $previous,
        );
    }
}
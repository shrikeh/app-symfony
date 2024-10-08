<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Exception;

use RuntimeException;
use Shrikeh\App\Command\CommandBus\Exception\CommandBusException;
use Shrikeh\App\Message\Command;
use Throwable;

final class ErrorHandlingCommand extends RuntimeException implements CommandBusException, SymfonyMessageBusException
{
    public const ExceptionMessage MSG = ExceptionMessage::ERROR_HANDLING_COMMAND;

    public function __construct(public readonly Command $command, Throwable $previous)
    {
        parent::__construct(
            message: self::MSG->message(get_class($this->command), $previous->getMessage()),
            previous: $previous,
        );
    }
}

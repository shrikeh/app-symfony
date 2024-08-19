<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Exception;

use Shrikeh\App\Message\Command;
use Shrikeh\App\Command\CommandBus\Exception\CommandBusException;
use RuntimeException;
use Shrikeh\SymfonyApp\Exception\ExceptionMessage;
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

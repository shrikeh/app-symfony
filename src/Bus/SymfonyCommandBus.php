<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus;

use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingCommand;
use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Result;
use Throwable;

final readonly class SymfonyCommandBus implements CommandBus
{

    public function __construct(private MessageBus $messageBus)
    {
    }

    public function handle(Command $command): Result
    {
        try {
            return $this->messageBus->message($command);
        } catch (Throwable $exc) {
            throw new ErrorHandlingCommand($command, $exc);
        }
    }
}

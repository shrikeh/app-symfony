<?php

declare(strict_types=1);

namespace RpHaven\App\Bus;

use RpHaven\App\Bus\Exception\ErrorHandlingCommand;
use RpHaven\App\Command\CommandBus;
use RpHaven\App\Message\Command;
use RpHaven\App\Message\Result;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final readonly class SymfonyCommandBus implements CommandBus
{

    public function __construct(private CorrelatingMessageBus $commandBus)
    {
    }

    public function handle(Command $command): ?Result
    {
        try {
            return $this->commandBus->message($command);
        } catch (Throwable $exc) {
            throw new ErrorHandlingCommand($command, $exc);
        }
    }
}
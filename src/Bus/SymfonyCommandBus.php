<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus;

use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingCommand;
use Shrikeh\SymfonyApp\Bus\Traits\ResultAssertion;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Throwable;

final class SymfonyCommandBus implements CommandBus
{
    use ResultAssertion;
    use HandleTrait {
        handle as private handleCommand;
    }

    /**
     * @param MessageBusInterface $messageBus
     * @phpstan-ignore property.onlyWritten
     */
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function handle(Command $command): Result
    {
        try {
            $result = $this->handleCommand($command);
        } catch (Throwable $exc) {
            throw new ErrorHandlingCommand($command, $exc);
        }

        $this->assertResult($command, $result);

        return $result;
    }
}

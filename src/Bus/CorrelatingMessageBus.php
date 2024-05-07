<?php

declare(strict_types=1);

namespace RpHaven\App\Bus;

use Psr\Log\LoggerInterface;
use RpHaven\App\Log;
use RpHaven\App\Message\Correlated;
use RpHaven\App\Message\Correlation;
use RpHaven\App\Message\Result;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use RpHaven\App\Message;

final class CorrelatingMessageBus
{
    use HandleTrait {
        handle as private handle;
    }

    private ?Correlation $correlation;
    public function __construct(
        MessageBusInterface $messageBus,
        private readonly Log $log,
    ) {
        $this->messageBus = $messageBus;
    }

    public function message(Message $message): ?Result
    {
        if ($message instanceof Correlated) {
            $this->correlationStart($message);
        }
        $result = $this->handle($message);
        if ($message instanceof Correlated) {
            $this->correlationEnd($message);
        }

        return $result;
    }

    private function correlationStart(Correlated $correlated): void
    {
        $correlation = $correlated->correlated();
        $this->log->info(sprintf(
            'Beginning handling of message: %s (time: %s)',
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_START);
    }

    private function correlationEnd(Correlated $correlated): void
    {
        $correlation = $correlated->correlated();
        $this->log->info(sprintf(
            'Ending handling of message: %s (time: %s)',
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_END);
    }
}
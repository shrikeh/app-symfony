<?php

declare(strict_types=1);

namespace Shrikeh\App\Bus;

use Psr\Log\LoggerInterface;
use Shrikeh\App\Log;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Result;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Shrikeh\App\Message;

final class CorrelatingMessageBus implements MessageBus
{

    public const string MSG_CORRELATION_START = 'Beginning handling of message: %s (time: %s)';
    public const string MSG_CORRELATION_END = 'Ending handling of message: %s (time: %s)';

    use HandleTrait {
        handle as private handle;
    }

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

        /** @var Result|null $result */
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
            self::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_START);
    }

    private function correlationEnd(Correlated $correlated): void
    {
        $correlation = $correlated->correlated();
        $this->log->info(sprintf(
            self::MSG_CORRELATION_END,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_END);
    }
}

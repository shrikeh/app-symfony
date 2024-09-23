<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shrikeh\App\Message;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Decorator\CorrelationCommandBus;
use Shrikeh\SymfonyApp\Bus\Decorator\CorrelationQueryBus;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use Shrikeh\SymfonyApp\Bus\SymfonyCommandBus;
use Shrikeh\SymfonyApp\Bus\SymfonyQueryBus;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;
use Shrikeh\SymfonyApp\Logger\Psr3AppLogger;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final readonly class CommandBusBench
{
    public function __construct(
        private LoggerInterface $logger = new NullLogger(),
        private CorrelationFactory $correlationFactory = new UlidCorrelation()
    ) {
    }

    public function benchCommandBus(): void
    {
        $messageBus = new MessageBus($this->middleware());

        $commandBus = new SymfonyCommandBus($messageBus);
        $logger = new Psr3AppLogger($this->logger);
        $correlatingCommandBus = new CorrelationCommandBus($commandBus, $logger);

        $command = $this->command()->withCorrelation(
            $this->correlationFactory->correlation()
        );

        $correlatingCommandBus->handle($command);
    }


    private function middleware(): iterable
    {
        yield new HandleMessageMiddleware(
            new HandlersLocator([
                '*' => [$this->handler()],
            ])
        );

        yield new CorrelatedMessage(
            new HandledEnvelope(),
            $this->logger,
        );
    }

    private function handler(): callable
    {
        return new class () {
            public function __invoke(Message & Correlated $command): Result & Correlated
            {
                return new class () implements Result, Correlated {
                    use WithCorrelation;
                };
            }
        };
    }

    private function command(): Command & Correlated
    {
        return new class () implements Command, Correlated
        {
            use WithCorrelation;
        };
    }
}

<?php

declare(strict_types=1);

namespace Tests\Benchmark;

use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Decorator\CorrelationQueryBus;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use Shrikeh\SymfonyApp\Bus\SymfonyQueryBus;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;
use Shrikeh\SymfonyApp\Logger\Psr3AppLogger;
use Symfony\Component\Messenger\Handler\HandlersLocator;
use Symfony\Component\Messenger\MessageBus;
use Symfony\Component\Messenger\Middleware\HandleMessageMiddleware;

final readonly class QueryBusBench
{
    public function __construct(
        private LoggerInterface $logger = new NullLogger(),
        private CorrelationFactory $correlationFactory = new UlidCorrelation()
    ) {
    }

    public function benchQueryBus(): void
    {
        $messageBus = new MessageBus($this->middleware());

        $queryBus = new SymfonyQueryBus($messageBus);
        $logger = new Psr3AppLogger($this->logger);
        $correlatingQueryBus = new CorrelationQueryBus($queryBus, $logger);

        $query = $this->query()->withCorrelation(
            $this->correlationFactory->correlation()
        );

        $correlatingQueryBus->handle($query);
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
            public function __invoke(Message & Correlated $query): Result & Correlated
            {
                return new class () implements Result, Correlated {
                    use WithCorrelation;
                };
            }
        };
    }

    private function query(): Query & Correlated
    {
        return new class () implements Query, Correlated
        {
            use WithCorrelation;
        };
    }
}

<?php

declare(strict_types=1);

namespace Tests\Benchmark\Benches;

use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;

final readonly class CorrelationBench
{
    private Message & Correlated $correlated;

    private CorrelationFactory $correlationFactory;

    public function __construct()
    {
        $this->correlated = $this->createCorrelated();
        $this->correlationFactory = new UlidCorrelation();
    }

    /**
     * @Assert("mode(variant.time.avg) < 10 microseconds +/- 10%")
     */
    public function benchCorrelation(): void
    {
        $this->correlated->withCorrelation(
            $this->correlationFactory->correlation()
        );
    }

    private function createCorrelated(): Correlated & Message
    {
        return new class () implements Correlated, Message {
            use WithCorrelation;
        };
    }
}

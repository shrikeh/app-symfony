<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;

use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Result;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

final readonly class StampHandler
{
    public function __construct(private Correlation $correlation, private HandledStamp $handledStamp)
    {
    }

    public function __invoke(StampInterface $stamp): StampInterface
    {
        if ($stamp === $this->handledStamp) {
            /** @var Result&Correlated $result */
            $result = $this->handledStamp->getResult();
            $stamp = new HandledStamp(
                $result->withCorrelation($this->correlation->update()),
                $this->handledStamp->getHandlerName(),
            );
        }

        return $stamp;
    }
}

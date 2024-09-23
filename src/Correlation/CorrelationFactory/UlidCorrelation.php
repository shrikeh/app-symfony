<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Correlation\CorrelationFactory;

use DateTimeImmutable;
use DateTimeInterface;
use Shrikeh\App\Message\Correlation;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\Id\CorrelationUlid;

final readonly class UlidCorrelation implements CorrelationFactory
{
    public function correlation(DateTimeInterface $dateTime = new DateTimeImmutable()): Correlation
    {
        return new Correlation(CorrelationUlid::init($dateTime), $dateTime);
    }
}

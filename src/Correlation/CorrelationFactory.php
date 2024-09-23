<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Correlation;

use DateTimeImmutable;
use DateTimeInterface;
use Shrikeh\App\Message\Correlation;

interface CorrelationFactory
{
    public function correlation(DateTimeInterface $dateTime = new DateTimeImmutable()): Correlation;
}

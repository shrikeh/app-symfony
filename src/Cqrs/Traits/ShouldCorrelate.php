<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Cqrs\Traits;

use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;

trait ShouldCorrelate
{
    /**
     * @param Message $message
     * @return bool
     * @psalm-assert-if-true Correlated $message
     */
    private function shouldCorrelate(Message $message): bool
    {
        return ($message instanceof Correlated && !$message->hasCorrelation());
    }
}

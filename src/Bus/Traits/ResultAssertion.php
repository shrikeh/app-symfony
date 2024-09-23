<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Traits;

use Shrikeh\App\Message;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Exception\MessageBusReturnedNoResult;

trait ResultAssertion
{
    /**
     * @param Message $message
     * @param mixed $result
     * @return void
     * @psalm-assert Result $result
     */
    private function assertResult(Message $message, mixed $result): void
    {
        if (!$result instanceof Result) {
            throw new MessageBusReturnedNoResult($message, $result);
        }
    }
}

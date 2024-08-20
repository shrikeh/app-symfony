<?php

/*
 * This file is part of Barney's Symfony skeleton for Domain-Driven Design
 *
 * (c) Barney Hanlon <symfony@shrikeh.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Decorator\Traits;

use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\CorrelatedMessageUncorrelated;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
trait AssertMessageCorrelation
{
    private function assertMessage(Correlated&Message $message): void
    {
        if (!$message->hasCorrelation()) {
            throw new CorrelatedMessageUncorrelated($message);
        }
    }
}

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

use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\BusMustReturnCorrelatedResult;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\CorrelatedResultWasUncorrelated;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\ResultCorrelationMismatch;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
trait AssertResultCorrelation
{
    /**
     * @phpstan-assert Result&Correlated $result
     */
    private function assertResult(Correlated&Message $message, Result $result): void
    {
        if (!$result instanceof Correlated) {
            throw new BusMustReturnCorrelatedResult($this->bus(), $result);
        }
        if (!$result->hasCorrelation()) {
            throw new CorrelatedResultWasUncorrelated($result);
        }

        if (!$message->correlated()->matches($result->correlated())) {
            throw new ResultCorrelationMismatch($message, $result);
        }
    }

    abstract private function bus(): CommandBus | QueryBus;
}

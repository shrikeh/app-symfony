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

namespace Shrikeh\SymfonyApp\Bus\Decorator;

use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Command\CommandBus\CorrelatingCommandBus;
use Shrikeh\App\Log;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\AssertMessageCorrelation;
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\AssertResultCorrelation;
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\CorrelationLog;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class CorrelationCommandBus implements CorrelatingCommandBus
{
    use AssertMessageCorrelation;
    use AssertResultCorrelation;
    use CorrelationLog;

    public function __construct(CommandBus $bus, Log $log)
    {
        $this->bus = $bus;
        $this->log = $log;
    }
    /**
     * Simple decorator that hardens the inner Command Bus by using the more strict interface definition.
     * @inheritDoc
     */
    public function handle(Correlated&Command $command): Result&Correlated
    {
        $this->assertMessage($command);
        $this->correlationStart($command);
        $result = $this->bus->handle($command);
        $this->assertResult($command, $result);
        $this->correlationEnd($command);
        /** @var Result&Correlated*/
        return $result;
    }
}

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

use Shrikeh\App\Log;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\App\Query\QueryBus\CorrelatingQueryBus;
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\AssertResultCorrelation;
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\CorrelationLog;
use Symfony\Component\DependencyInjection\Attribute\AsDecorator;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
#[AsDecorator(
    decorates: QueryBus::class
)]
final readonly class CorrelationQueryBus implements CorrelatingQueryBus
{
    use AssertResultCorrelation;
    use CorrelationLog;

    public function __construct(QueryBus $bus, Log $log)
    {
        $this->bus = $bus;
        $this->log = $log;
    }

    /**
     * Simple decorator that hardens the inner Query Bus by using the more strict interface definition.
     * @inheritDoc
     */
    public function handle(Correlated&Query $query): Result&Correlated
    {
        $this->correlationStart($query);
        $result = $this->bus->handle($query);
        $this->correlationEnd($query);
        $this->assertResult($query, $result);

        /** @var Result&Correlated*/
        return $result;
    }
}

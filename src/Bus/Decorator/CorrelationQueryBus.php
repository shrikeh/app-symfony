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
use Shrikeh\SymfonyApp\Bus\Decorator\Traits\AssertMessageCorrelation;
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
    use AssertMessageCorrelation;
    use AssertResultCorrelation;
    use CorrelationLog;

    public function __construct(private QueryBus $bus, private Log $log)
    {
    }

    /**
     * Simple decorator that hardens the inner Query Cqrs by using the more strict interface definition.
     * @inheritDoc
     */
    public function handle(Correlated&Query $query): Result&Correlated
    {
        $this->assertMessage($query);
        $this->correlationStart($query);
        $result = $this->bus()->handle($query);
        $this->assertResult($query, $result);
        $this->correlationEnd($query);

        /** @var Result&Correlated*/
        return $result;
    }

    private function bus(): QueryBus
    {
        return $this->bus;
    }
}

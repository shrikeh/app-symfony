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

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Log;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\SymfonyApp\Bus\BusContext;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\BusMustReturnCorrelatedResult;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\CorrelatedMessageUncorrelated;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\CorrelatedResultWasUncorrelated;
use Shrikeh\SymfonyApp\Bus\Decorator\Exception\ResultCorrelationMismatch;
use Shrikeh\SymfonyApp\Correlation\Id\CorrelationUlid;
use Shrikeh\SymfonyApp\Exception\ExceptionMessage;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CorrelationQueryBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());
        $query = $this->correlatedQuery()->withCorrelation($correlation);

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(true);
        $result->correlated()->willReturn($correlation);

        $inner = $this->prophesize(QueryBus::class);
        $result = $result->reveal();
        $inner->handle($query)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationQueryBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationQueryBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $log->info(sprintf(
            CorrelationQueryBus::MSG_CORRELATION_END,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationQueryBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_END)->shouldBeCalledOnce();

        $decorator = new CorrelationQueryBus($inner->reveal(), $log->reveal());

        $this->assertSame($result, $decorator->handle($query));
    }

    public function testItThrowsAnExceptionIfTheQueryIsUncorrelated(): void
    {
        $query = $this->correlatedQuery();
        $inner = $this->prophesize(QueryBus::class)->reveal();
        $log = $this->prophesize(Log::class)->reveal();

        $decorator = new CorrelationQueryBus($inner, $log);

        $this->expectExceptionObject(new CorrelatedMessageUncorrelated($query));
        $this->expectExceptionMessage(CorrelatedMessageUncorrelated::MSG->message(get_class($query)));
        $decorator->handle($query);
    }

    public function testItThrowsAnExceptionIfTheResultIsNotCorrelated(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());
        $query = $this->correlatedQuery()->withCorrelation($correlation);

        $result = $this->prophesize(Result::class);

        $inner = $this->prophesize(QueryBus::class);
        $result = $result->reveal();
        $inner->handle($query)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationQueryBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationQueryBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $inner = $inner->reveal();
        $decorator = new CorrelationQueryBus($inner, $log->reveal());

        $this->expectExceptionObject(new BusMustReturnCorrelatedResult($inner, $result));
        $this->expectExceptionMessage(ExceptionMessage::RESULT_NOT_CORRELATED->message(
            get_class($inner),
            get_class($result),
        ));

        $decorator->handle($query);
    }

    public function testItThrowsAnExceptionIfTheResultDoesNotHaveACorrelation(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());
        $query = $this->correlatedQuery()->withCorrelation($correlation);

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(false);

        $inner = $this->prophesize(QueryBus::class);
        $result = $result->reveal();
        $inner->handle($query)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationQueryBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationQueryBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $inner = $inner->reveal();
        $decorator = new CorrelationQueryBus($inner, $log->reveal());

        $this->expectExceptionObject(new CorrelatedResultWasUncorrelated($result));
        $this->expectExceptionMessage(ExceptionMessage::CORRELATED_RESULT_UNCORRELATED->message(
            get_class($result)
        ));

        $decorator->handle($query);
    }

    public function testItThrowsAnExceptionIfTheResultCorrelationDoesNotMatchTheCommandCorrelation(): void
    {
        $queryCorrelation = new Correlation(CorrelationUlid::init());
        $query = $this->correlatedQuery()->withCorrelation($queryCorrelation);

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(true);
        $result->correlated()->willReturn(new Correlation(CorrelationUlid::init()));

        $inner = $this->prophesize(QueryBus::class);
        $result = $result->reveal();
        $inner->handle($query)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationQueryBus::MSG_CORRELATION_START,
            $queryCorrelation->correlationId->toString(),
            $queryCorrelation->dateTime->format(CorrelationQueryBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();


        $inner = $inner->reveal();
        $decorator = new CorrelationQueryBus($inner, $log->reveal());

        $this->expectExceptionObject(new ResultCorrelationMismatch($query, $result));
        $this->expectExceptionMessage(ExceptionMessage::CORRELATION_MISMATCH->message(
            $query->correlated()->correlationId->toString(),
            $result->correlated()->correlationId->toString(),
        ));

        $decorator->handle($query);
    }

    private function correlatedQuery(): Query&Correlated
    {
        return new class () implements Query, Correlated {
            use WithCorrelation;
        };
    }
}

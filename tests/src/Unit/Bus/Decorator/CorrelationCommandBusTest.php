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
use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Log;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Result;
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
final class CorrelationCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(true);
        $result->correlated()->willReturn($correlation);

        $inner = $this->prophesize(CommandBus::class);
        $command = $this->correlatedCommand()->withCorrelation($correlation);
        $result = $result->reveal();
        $inner->handle($command)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationCommandBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationCommandBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $log->info(sprintf(
            CorrelationCommandBus::MSG_CORRELATION_END,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationCommandBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_END)->shouldBeCalledOnce();

        $decorator = new CorrelationCommandBus($inner->reveal(), $log->reveal());

        $this->assertSame($result, $decorator->handle($command));
    }

    public function testItThrowsAnExceptionIfTheCommandIsUncorrelated(): void
    {
        $command = $this->correlatedCommand();
        $inner = $this->prophesize(CommandBus::class)->reveal();
        $log = $this->prophesize(Log::class)->reveal();

        $decorator = new CorrelationCommandBus($inner, $log);

        $this->expectExceptionObject(new CorrelatedMessageUncorrelated($command));
        $this->expectExceptionMessage(CorrelatedMessageUncorrelated::MSG->message(get_class($command)));
        $decorator->handle($command);
    }

    public function testItThrowsAnExceptionIfTheResultIsNotCorrelated(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());

        $result = $this->prophesize(Result::class);

        $inner = $this->prophesize(CommandBus::class);
        $command = $this->correlatedCommand()->withCorrelation($correlation);
        $result = $result->reveal();
        $inner->handle($command)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationCommandBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationCommandBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();


        $inner = $inner->reveal();
        $decorator = new CorrelationCommandBus($inner, $log->reveal());

        $this->expectExceptionObject(new BusMustReturnCorrelatedResult($inner, $result));
        $this->expectExceptionMessage(ExceptionMessage::RESULT_NOT_CORRELATED->message(
            get_class($inner),
            get_class($result)
        ));

        $decorator->handle($command);
    }

    public function testItThrowsAnExceptionIfTheResultDoesNotHaveACorrelation(): void
    {
        $correlation = new Correlation(CorrelationUlid::init());
        $command = $this->correlatedCommand()->withCorrelation($correlation);

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(false);

        $inner = $this->prophesize(CommandBus::class);
        $result = $result->reveal();
        $inner->handle($command)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationCommandBus::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(CorrelationCommandBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();
        $inner = $inner->reveal();
        $decorator = new CorrelationCommandBus($inner, $log->reveal());

        $this->expectExceptionObject(new CorrelatedResultWasUncorrelated($result));
        $this->expectExceptionMessage(ExceptionMessage::CORRELATED_RESULT_UNCORRELATED->message(
            get_class($result)
        ));

        $decorator->handle($command);
    }

    public function testItThrowsAnExceptionIfTheResultCorrelationDoesNotMatchTheCommandCorrelation(): void
    {
        $commandCorrelation = new Correlation(CorrelationUlid::init());
        $command = $this->correlatedCommand()->withCorrelation($commandCorrelation);

        $result = $this->prophesize(Correlated::class);
        $result->willImplement(Result::class);
        $result->hasCorrelation()->willReturn(true);
        $result->correlated()->willReturn(new Correlation(CorrelationUlid::init()));

        $inner = $this->prophesize(CommandBus::class);
        $result = $result->reveal();
        $inner->handle($command)->willReturn($result);

        $log = $this->prophesize(Log::class);
        $log->info(sprintf(
            CorrelationCommandBus::MSG_CORRELATION_START,
            $commandCorrelation->correlationId->toString(),
            $commandCorrelation->dateTime->format(CorrelationCommandBus::LOG_FORMAT_DATETIME)
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $inner = $inner->reveal();
        $decorator = new CorrelationCommandBus($inner, $log->reveal());

        $this->expectExceptionObject(new ResultCorrelationMismatch($command, $result));
        $this->expectExceptionMessage(ExceptionMessage::CORRELATION_MISMATCH->message(
            $command->correlated()->correlationId->toString(),
            $result->correlated()->correlationId->toString(),
        ));

        $decorator->handle($command);
    }

    private function correlatedCommand(): Command&Correlated
    {
        return new class () implements Command, Correlated {
            use WithCorrelation;
        };
    }
}

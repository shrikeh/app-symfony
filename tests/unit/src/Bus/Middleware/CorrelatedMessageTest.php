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

namespace Tests\Unit\Bus\Middleware;

use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use Shrikeh\App\Command\CommandBus;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage;
use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CorrelatedMessageTest extends TestCase
{
    use ProphecyTrait;

    private CorrelationFactory $correlationFactory;

    protected function setUp(): void
    {
        $this->correlationFactory = new CorrelationFactory\UlidCorrelation();
    }

    public function testItDoesNothingIfTheResultIsUncorrelated(): void
    {
        $handlerName = 'FooBarBaz';
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $logger = $this->prophesize(LoggerInterface::class)->reveal();
        $stack = $this->prophesize(StackInterface::class)->reveal();

        $handledStamp = new HandledStamp($result, $handlerName);
        $envelope = new Envelope($command, [$handledStamp]);

        $correlatedMessage = new CorrelatedMessage(new HandledEnvelope(), $logger);

        $this->assertSame($envelope, $correlatedMessage->handle($envelope, $stack));
    }

    public function testItIgnoresACorrelatedResult(): void
    {
        $handlerName = 'FooBarBaz';
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->correlatedResult()->withCorrelation($this->correlationFactory->correlation());
        $logger = $this->prophesize(LoggerInterface::class)->reveal();
        $stack = $this->prophesize(StackInterface::class)->reveal();

        $handledStamp = new HandledStamp($result, $handlerName);
        $envelope = new Envelope($command, [$handledStamp]);

        $correlatedMessage = new CorrelatedMessage(new HandledEnvelope(), $logger);

        $this->assertSame($envelope, $correlatedMessage->handle($envelope, $stack));
    }

    public function testItAddsACorrelationToACorrelatedResultWithoutCorrelation(): void
    {
        $handlerName = 'FooBarBaz';
        $correlation = $this->correlationFactory->correlation();
        $command = new class () implements Command, Correlated {
            use WithCorrelation;
        };
        $command = $command->withCorrelation($correlation);
        $result = $this->correlatedResult();
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->debug(Argument::containingString(get_class($result)))->shouldBeCalledOnce();
        $stack = $this->prophesize(StackInterface::class)->reveal();

        $handledStamp = new HandledStamp($result, $handlerName);
        $envelope = new Envelope($command, [$handledStamp]);

        $correlatedMessage = new CorrelatedMessage(new HandledEnvelope(), $logger->reveal());

        /** @var HandledStamp $handledLast */
        $handledLast = $correlatedMessage->handle($envelope, $stack)->last(HandledStamp::class);

        $this->assertTrue($correlation->matches($handledLast->getResult()->correlated()));
    }

    private function correlatedResult(): Result&Correlated
    {
        return new class () implements Result, Correlated {
            use WithCorrelation;
        };
    }
}

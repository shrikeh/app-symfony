<?php

declare(strict_types=1);

namespace Tests\Unit\Bus\Middleware;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use Shrikeh\SymfonyApp\Correlation\Id\CorrelationUlid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class CorrelatedMessageTest extends TestCase
{
    use ProphecyTrait;

    public function testItAddsACorrelation(): void
    {
        $query = new class implements Query, Correlated {
            use WithCorrelation;
        };

        $result = new class () implements Result, Correlated {
            use WithCorrelation;
        };

        $logger = $this->prophesize(LoggerInterface::class);
        $logger->debug(sprintf(CorrelatedMessage::MSG_DEBUG, get_class($result)))
            ->shouldBeCalledOnce();
        $stack = $this->prophesize(StackInterface::class)->reveal();
        $handledEnvelope = new HandledEnvelope();
        $correlationId = CorrelationUlid::init();
        $correlation = new Correlation($correlationId);
        $query = $query->withCorrelation($correlation);

        $envelope = new Envelope(
            $query,
            [
                new HandledStamp($result, 'foo')
            ]
        );

        $correlatedMessage = new CorrelatedMessage(
            $handledEnvelope,
            $logger->reveal()
        );

        $updatedEnvelope = $correlatedMessage->handle(
            $envelope,
            $stack
        );

        $this->assertTrue($correlation->matches(
            $updatedEnvelope->last(HandledStamp::class)->getResult()->correlated()
        ));
    }

    public function testItIgnoresUncorrelatedMessages(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $logger = $this->prophesize(LoggerInterface::class);
        $logger->debug(Argument::type('string'))->shouldNotBeCalled();
        $stack = $this->prophesize(StackInterface::class)->reveal();
        $handledEnvelope = new HandledEnvelope();


        $envelope = new Envelope(
            $command,
            [
                new HandledStamp($result, 'foo')
            ]
        );

        $correlatedMessage = new CorrelatedMessage(
            $handledEnvelope,
            $logger->reveal()
        );

        $this->assertSame($envelope, $correlatedMessage->handle(
            $envelope,
            $stack
        ));
    }
}

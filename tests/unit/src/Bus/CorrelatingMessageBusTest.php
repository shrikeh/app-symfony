<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Bus\BusContext;
use Shrikeh\App\Bus\CorrelatingMessageBus;
use Shrikeh\App\Log;
use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Result;
use Shrikeh\App\Uid\Id\Ulid\CorrelationUlid;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class CorrelatingMessageBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItCanCorrelate(): void
    {
        $message = $this->prophesize(Message::class);
        $message->willImplement(Correlated::class);
        $result = $this->prophesize(Result::class)->reveal();
        $log = $this->prophesize(Log::class);

        $correlation = new Correlation(CorrelationUlid::init());

        $message->correlated()->willReturn($correlation);

        $stamp = new HandledStamp($result, 'test');
        $envelope = new Envelope($message, [$stamp]);

        $messageBus = $this->prophesize(MessageBusInterface::class);

        $log->info(sprintf(
            CorrelatingMessageBus::MSG_CORRELATION_START,
            $correlation->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_START)->shouldBeCalledOnce();

        $log->info(sprintf(
            CorrelatingMessageBus::MSG_CORRELATION_END,
            $correlation->toString(),
            $correlation->dateTime->format(DATE_ATOM),
        ), BusContext::MESSAGE_END)->shouldBeCalledOnce();

        $messageBus->dispatch($message)->willReturn($envelope);

        $correlatingMessageBus = new CorrelatingMessageBus(
            $messageBus->reveal(),
            $log->reveal(),
        );

        /** @var Correlated & Message $message */
        $message = $message->reveal();
        $this->assertSame(
            $result,
            $correlatingMessageBus->message($message),
        );
    }

    public function testItCanReturnNull(): void
    {
        $log = $this->prophesize(Log::class);
        $message = $this->prophesize(Message::class)->reveal();
        $stamp = new HandledStamp(null, 'test');
        $envelope = new Envelope($message, [$stamp]);

        $messageBus = $this->prophesize(MessageBusInterface::class);

        $messageBus->dispatch($message)->willReturn($envelope);

        $correlatingMessageBus = new CorrelatingMessageBus(
            $messageBus->reveal(),
            $log->reveal(),
        );

        $this->assertNull(
            $correlatingMessageBus->message($message),
        );
    }
}

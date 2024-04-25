<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use Prophecy\PhpUnit\ProphecyTrait;
use RpHaven\App\Bus\Exception\ErrorHandlingCommand;
use RpHaven\App\Bus\SymfonyCommandBus;
use PHPUnit\Framework\TestCase;
use RpHaven\App\Command;
use RpHaven\App\Result;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

final class SymfonyCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $stamp = new HandledStamp($result, 'test');

        $envelope = new Envelope($command, [$stamp]);

        $messageBus = $this->prophesize(MessageBusInterface::class);

        $messageBus->dispatch($command)->willReturn($envelope);

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $commandBus->handle($command)
        );
    }

    public function testItCanReturnNull(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $stamp = new HandledStamp(null, 'test');

        $envelope = new Envelope($command, [$stamp]);

        $messageBus = $this->prophesize(MessageBusInterface::class);

        $messageBus->dispatch($command)->willReturn($envelope);

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->assertNull($commandBus->handle($command));
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $exception = new LogicException('foo');
        $messageBus->dispatch($command)->willThrow($exception);
        $this->expectExceptionObject(new ErrorHandlingCommand($command, $exception));

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $commandBus->handle($command);
    }
}

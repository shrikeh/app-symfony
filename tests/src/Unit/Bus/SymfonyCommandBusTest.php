<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingCommand;
use Shrikeh\SymfonyApp\Bus\Exception\MessageBusReturnedNoResult;
use Shrikeh\SymfonyApp\Bus\MessageBus;
use Shrikeh\SymfonyApp\Bus\SymfonyCommandBus;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\SymfonyQueryBus;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();

        $messageBus = $this->prophesize(MessageBusInterface::class);

        $messageBus->dispatch($command)->willReturn(
            new Envelope($command, [
                new HandledStamp($result, 'test')
            ])
        );

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $commandBus->handle($command)
        );
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $exception = new LogicException('foo');
        $messageBus->dispatch($command)->willThrow($exception);

        $this->expectExceptionObject(new ErrorHandlingCommand($command, $exception));
        $this->expectExceptionMessage(ErrorHandlingCommand::MSG->message(
            get_class($command),
            $exception->getMessage(),
        ));

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $commandBus->handle($command);
    }

    public function testItThrowsAnExceptionIfThereIsNoResult(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);
        $messageBus->dispatch($command)->willReturn(
            new Envelope($command, [
                new HandledStamp(null, 'test')
            ])
        );

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->expectException(MessageBusReturnedNoResult::class);
        $this->expectExceptionMessage(MessageBusReturnedNoResult::MSG->message(
            get_class($command),
            (string) null,
        ));

        $commandBus->handle($command);
    }
}

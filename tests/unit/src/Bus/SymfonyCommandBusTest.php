<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Bus\Exception\ErrorHandlingCommand;
use Shrikeh\App\Bus\MessageBus;
use Shrikeh\App\Bus\SymfonyCommandBus;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Result;
use Symfony\Component\Messenger\Exception\LogicException;

final class SymfonyCommandBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();

        $messageBus = $this->prophesize(MessageBus::class);

        $messageBus->message($command)->willReturn($result);

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $commandBus->handle($command)
        );
    }

    public function testItCanReturnNull(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBus::class);

        $messageBus->message($command)->willReturn(null);

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $this->assertNull($commandBus->handle($command));
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $command = $this->prophesize(Command::class)->reveal();
        $messageBus = $this->prophesize(MessageBus::class);

        $exception = new LogicException('foo');
        $messageBus->message($command)->willThrow($exception);

        $this->expectExceptionObject(new ErrorHandlingCommand($command, $exception));
        $this->expectExceptionMessage(sprintf(
            ErrorHandlingCommand::MSG_FORMAT,
            get_class($command),
            $exception->getMessage(),
        ));

        $commandBus = new SymfonyCommandBus($messageBus->reveal());

        $commandBus->handle($command);
    }
}

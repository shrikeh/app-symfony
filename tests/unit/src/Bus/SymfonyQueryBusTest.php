<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use RpHaven\App\Bus\Exception\ErrorHandlingQuery;
use RpHaven\App\Bus\SymfonyQueryBus;
use RpHaven\App\Query;
use RpHaven\App\Result;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\LogicException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class SymfonyQueryBusTest extends TestCase
{

    use ProphecyTrait;
    public function testItReturnsAResult(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();
        $stamp = new HandledStamp($result, 'test');

        $envelope = new Envelope($query, [$stamp]);

        $messageBus = $this->prophesize(MessageBusInterface::class);
        $messageBus->dispatch($query)->willReturn($envelope);

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $queryBus->handle($query)
        );
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $exception = new LogicException('foo');
        $messageBus->dispatch($query)->willThrow($exception);
        $this->expectExceptionObject(new ErrorHandlingQuery($query, $exception));

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $queryBus->handle($query);
    }
}

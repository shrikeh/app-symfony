<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingQuery;
use Shrikeh\SymfonyApp\Bus\Exception\MessageBusReturnedNoResult;
use Shrikeh\SymfonyApp\Bus\Exception\QueryMustReturnAResult;
use Shrikeh\SymfonyApp\Bus\MessageBus;
use Shrikeh\SymfonyApp\Bus\SymfonyQueryBus;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
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

        $messageBus = $this->prophesize(MessageBusInterface::class);
        $messageBus->dispatch($query)->willReturn(
            new Envelope($query, [
                new HandledStamp($result, 'test')
            ])
        );

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $queryBus->handle($query),
        );
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);

        $exception = new LogicException('foo');
        $messageBus->dispatch($query)->willThrow($exception);
        $this->expectExceptionObject(new ErrorHandlingQuery($query, $exception));
        $this->expectExceptionMessage(ErrorHandlingQuery::MSG->message(
            get_class($query),
            $exception->getMessage(),
        ));
        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $queryBus->handle($query);
    }

    public function testItThrowsAnExceptionIfThereIsNoResult(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $messageBus = $this->prophesize(MessageBusInterface::class);
        $messageBus->dispatch($query)->willReturn(
            new Envelope($query, [
                new HandledStamp(null, 'test')
            ])
        );

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $this->expectException(MessageBusReturnedNoResult::class);
        $this->expectExceptionMessage(MessageBusReturnedNoResult::MSG->message(
            get_class($query),
            (string) null,
        ));

        $queryBus->handle($query);
    }
}

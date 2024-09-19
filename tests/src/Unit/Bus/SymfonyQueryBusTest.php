<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\SymfonyApp\Bus\Exception\ErrorHandlingQuery;
use Shrikeh\SymfonyApp\Bus\Exception\QueryMustReturnAResult;
use Shrikeh\SymfonyApp\Bus\MessageBus;
use Shrikeh\SymfonyApp\Bus\SymfonyQueryBus;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Symfony\Component\Messenger\Exception\LogicException;

final class SymfonyQueryBusTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsAResult(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $result = $this->prophesize(Result::class)->reveal();

        $messageBus = $this->prophesize(MessageBus::class);
        $messageBus->message($query)->willReturn($result);

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $this->assertSame(
            $result,
            $queryBus->handle($query),
        );
    }

    public function testItThrowsAnExceptionIfTheMessageBusThrowsAnException(): void
    {
        $query = $this->prophesize(Query::class)->reveal();
        $messageBus = $this->prophesize(MessageBus::class);

        $exception = new LogicException('foo');
        $messageBus->message($query)->willThrow($exception);
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
        $messageBus = $this->prophesize(MessageBus::class);
        $messageBus->message($query)->willReturn(null);

        $queryBus = new SymfonyQueryBus($messageBus->reveal());

        $this->expectException(QueryMustReturnAResult::class);
        $this->expectExceptionMessage(QueryMustReturnAResult::MSG->message(get_class($query)));

        $queryBus->handle($query);
    }
}

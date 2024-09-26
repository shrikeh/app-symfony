<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Action\Cqrs\QueryFactory\Decorator;

use DateTimeImmutable;
use DateTimeInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator\CorrelatedQuery;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator\CorrelatedQuery\Exception\QueryNotCorrelatable;
use Shrikeh\SymfonyApp\Http\Exception\ExceptionMessage;

final class CorrelatedQueryTest extends TestCase
{
    use ProphecyTrait;

    public function testItAddsACorrelation(): void
    {
        $correlation = (new CorrelationFactory\UlidCorrelation())->correlation();
        $inner = $this->prophesize(QueryFactory::class);
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();
        $query = new class () implements Query, Correlated {
            use WithCorrelation;
        };

        $inner->build($request)->willReturn($query);
        $correlationFactory = $this->correlationFactory($correlation);

        $correlatedQueryDecorator = new CorrelatedQuery(
            $inner->reveal(),
            $correlationFactory
        );

        $this->assertSame($correlation, $correlatedQueryDecorator->build(
            $request
        )->correlated());
    }

    public function testItIgnoresACommandWithAnExistingCorrelation(): void
    {
        $correlation = (new CorrelationFactory\UlidCorrelation())->correlation();
        $inner = $this->prophesize(QueryFactory::class);
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();
        $query = new class () implements Query, Correlated {
            use WithCorrelation;
        };

        $query = $query->withCorrelation($correlation);

        $inner->build($request)->willReturn($query);

        $correlatedQueryDecorator = new CorrelatedQuery(
            $inner->reveal(),
            $this->correlationFactory($correlation),
        );

        $this->assertSame($query, $correlatedQueryDecorator->build($request));
    }

    public function testItThrowsAnExceptionIfTheQueryIsNotCorrelatable(): void
    {
        $correlation = (new CorrelationFactory\UlidCorrelation())->correlation();
        $inner = $this->prophesize(QueryFactory::class);
        $request = $this->prophesize(ServerRequestInterface::class)->reveal();
        $query = $this->prophesize(Query::class)->reveal();

        $inner->build($request)->willReturn($query);

        $correlatedQueryDecorator = new CorrelatedQuery(
            $inner->reveal(),
            $this->correlationFactory($correlation),
        );

        $this->expectException(QueryNotCorrelatable::class);
        $this->expectExceptionMessage(ExceptionMessage::UNCORRELATABLE_QUERY->message(
            get_class($query),
            Correlated::class,
        ));

        $correlatedQueryDecorator->build($request);
    }

    private function correlationFactory(?Correlation $correlation = null): CorrelationFactory
    {
        return new class ($correlation) implements CorrelationFactory {
            public function __construct(private ?Correlation $correlation = null)
            {
            }
            public function correlation(DateTimeInterface $dateTime = new DateTimeImmutable()): Correlation
            {
                return $this->correlation;
            }
        };
    }
}

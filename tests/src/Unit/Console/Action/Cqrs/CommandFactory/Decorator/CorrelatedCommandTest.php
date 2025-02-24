<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Action\Cqrs\CommandFactory\Decorator;

use DateTimeImmutable;
use DateTimeInterface;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\AdrContracts\MessageFactory\Console\ConsoleCommandFactory;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory\Decorator\CorrelatedCommand;
use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class CorrelatedCommandTest extends TestCase
{
    use ProphecyTrait;

    public function testItAddsACorrelation(): void
    {
        $correlation = (new UlidCorrelation())->correlation();
        $inner = $this->prophesize(ConsoleCommandFactory::class);
        $input = $this->prophesize(InputInterface::class)->reveal();
        $output = $this->prophesize(OutputInterface::class)->reveal();
        $command = new class () implements Command, Correlated {
            use WithCorrelation;
        };
        $inner->build($input, $output)->willReturn($command);
        $correlationFactory = $this->correlationFactory($correlation);

        $correlatedCommandDecorator = new CorrelatedCommand(
            $inner->reveal(),
            $correlationFactory
        );

        $this->assertSame($correlation, $correlatedCommandDecorator->build(
            $input,
            $output
        )->correlated());
    }

    public function testItIgnoresACommandWithAnExistingCorrelation(): void
    {
        $correlation = (new UlidCorrelation())->correlation();
        $inner = $this->prophesize(ConsoleCommandFactory::class);
        $input = $this->prophesize(InputInterface::class)->reveal();
        $output = $this->prophesize(OutputInterface::class)->reveal();
        $command = new class () implements Command, Correlated {
            use WithCorrelation;
        };

        $command = $command->withCorrelation($correlation);

        $inner->build($input, $output)->willReturn($command);

        $correlatedCommandDecorator = new CorrelatedCommand(
            $inner->reveal(),
            $this->correlationFactory($correlation),
        );

        $this->assertSame($command, $correlatedCommandDecorator->build($input, $output));
    }

    private function correlationFactory(Correlation $correlation): CorrelationFactory
    {
        return new class ($correlation) implements CorrelationFactory {
            public function __construct(private Correlation $correlation)
            {
            }
            public function correlation(DateTimeInterface $dateTime = new DateTimeImmutable()): Correlation
            {
                return $this->correlation;
            }
        };
    }
}

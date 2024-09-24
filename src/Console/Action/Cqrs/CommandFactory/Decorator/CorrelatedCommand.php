<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory\Decorator;

use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory\CorrelatedCommandFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Cqrs\Traits\ShouldCorrelate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class CorrelatedCommand implements CorrelatedCommandFactory
{
    use ShouldCorrelate;

    public function __construct(
        private CommandFactory $inner,
        private CorrelationFactory $correlationFactory
    ) {
    }

    public function build(InputInterface $input, OutputInterface $output): Command&Correlated
    {
        $command = $this->inner->build($input, $output);

        if ($this->shouldCorrelate($command)) {
            $command = $command->withCorrelation($this->correlationFactory->correlation());
        }

        return $command;
    }
}

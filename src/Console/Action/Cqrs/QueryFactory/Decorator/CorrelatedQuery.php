<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\QueryFactory\Decorator;

use Shrikeh\AdrContracts\MessageFactory\Console\ConsoleQueryFactory;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\QueryFactory\CorrelatedQueryFactory;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Cqrs\Traits\ShouldCorrelate;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final readonly class CorrelatedQuery implements CorrelatedQueryFactory
{
    use ShouldCorrelate;

    public function __construct(
        private ConsoleQueryFactory $inner,
        private CorrelationFactory $correlationFactory
    ) {
    }

    public function build(InputInterface $input, ?OutputInterface $output = null): Query&Correlated
    {
        $query = $this->inner->build($input, $output);

        if ($this->shouldCorrelate($query)) {
            /** @var Query&Correlated $query */
            $query = $query->withCorrelation($this->correlationFactory->correlation());
        }

        return $query;
    }
}

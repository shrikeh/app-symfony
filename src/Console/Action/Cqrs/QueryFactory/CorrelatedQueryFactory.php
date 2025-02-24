<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\QueryFactory;

use Shrikeh\AdrContracts\MessageFactory\Console\ConsoleQueryFactory;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CorrelatedQueryFactory extends ConsoleQueryFactory
{
    public function build(InputInterface $input, ?OutputInterface $output = null): Query&Correlated;
}

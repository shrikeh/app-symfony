<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\QueryFactory;

use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\QueryFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CorrelatedQueryFactory extends QueryFactory
{
    public function build(InputInterface $input, OutputInterface $output): Query&Correlated;
}

<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory;

use Shrikeh\AdrContracts\MessageFactory\Console\ConsoleCommandFactory;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CorrelatedCommandFactory extends ConsoleCommandFactory
{
    public function build(InputInterface $input, ?OutputInterface $output = null): Command&Correlated;
}

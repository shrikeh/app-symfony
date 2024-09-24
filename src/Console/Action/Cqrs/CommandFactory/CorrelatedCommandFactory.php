<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory;

use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\CommandFactory;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CorrelatedCommandFactory extends CommandFactory
{
    public function build(InputInterface $input, OutputInterface $output): Command&Correlated;
}

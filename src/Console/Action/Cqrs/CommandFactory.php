<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs;

use Shrikeh\App\Message\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface CommandFactory
{
    public function build(InputInterface $input, OutputInterface $output): Command;
}

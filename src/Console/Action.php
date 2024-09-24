<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface Action
{
    public function run(InputInterface $input, OutputInterface $output): int;
}

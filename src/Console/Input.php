<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console;

use Ds\Map;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface Input
{
    public function add(InputDefinition $definition): InputDefinition;

    public function extract(InputInterface $input, OutputInterface $output, ?Map $data = new Map()): Map;
}

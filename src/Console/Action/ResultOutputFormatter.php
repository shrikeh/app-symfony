<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action;

use Shrikeh\App\Message\Result;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

interface ResultOutputFormatter
{
    public function render(
        Result $result,
        InputInterface $input,
        OutputInterface $output
    ): void;
}

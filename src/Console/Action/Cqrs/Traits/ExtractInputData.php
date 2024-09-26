<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Cqrs\Traits;

use Ds\Map;
use Shrikeh\SymfonyApp\Console\Input;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait ExtractInputData
{
    private function extractFromInputs(
        InputInterface $input,
        OutputInterface $output,
        Map $data = new Map()
    ): Map {
        foreach ($this->inputs() as $expectedInput) {
            $data = $expectedInput->extract($input, $output, $data);
        }

        return $data;
    }

    /**
     * @return iterable<Input>
     */
    abstract private function inputs(): iterable;
}

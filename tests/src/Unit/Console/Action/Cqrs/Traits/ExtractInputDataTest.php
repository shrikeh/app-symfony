<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Action\Cqrs\Traits;

use Ds\Map;
use Ds\Set;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\SymfonyApp\Console\Action\Cqrs\Traits\ExtractInputData;
use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Console\Input;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ExtractInputDataTest extends TestCase
{
    use ProphecyTrait;

    public function testItExtractsInputData(): void
    {
        $inputs = new Set([$this->input(), $this->input()]);
        $trait = new class ($inputs)
        {
            use ExtractInputData {
                extractFromInputs as public;
            }

            public function __construct(private Set $inputs)
            {
            }
            private function inputs(): iterable
            {
                yield from $this->inputs;
            }
        };

        $input = $this->prophesize(InputInterface::class)->reveal();
        $output = $this->prophesize(OutputInterface::class)->reveal();

        $data = $trait->extractFromInputs($input, $output);

        foreach ($inputs as $extracted) {
            $this->assertTrue($extracted->called);
        }
    }

    private function input(): Input
    {
        return new class () implements Input {
            public bool $called = false;

            public function add(InputDefinition $definition): InputDefinition
            {
                return $definition;
            }

            public function extract(InputInterface $input, OutputInterface $output, ?Map $data = new Map()): Map
            {
                $this->called = true;

                return $data;
            }
        };
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Console\Action\Configurator;

use Ds\Map;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\SymfonyApp\Console\Action\ConfigurableAction;
use Shrikeh\SymfonyApp\Console\Action\Configurator\InputConfigurator;
use Shrikeh\SymfonyApp\Console\Input;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class InputConfiguratorTest extends TestCase
{
    use ProphecyTrait;

    public function testItConfiguresAnAction(): void
    {
        $inputDefinition = new InputDefinition();
        $configurableAction = $this->prophesize(ConfigurableAction::class);
        $configurableAction->getDefinition()->willReturn($inputDefinition);
        $configurableAction->setDefinition($inputDefinition)->willReturn($configurableAction->reveal());

        $input1 = $this->input();
        $input2 = $this->input();

        $inputConfigurator = InputConfigurator::fromServiceTagIterator([$input1, $input2]);

        $inputConfigurator($configurableAction->reveal());

        $configurableAction->setDefinition($inputDefinition)->shouldHaveBeenCalledOnce();

        $this->assertTrue($input1->called);
        $this->assertTrue($input2->called);
    }

    private function input(): Input
    {
        return new class () implements Input {
            public bool $called = false;

            public function add(InputDefinition $definition): InputDefinition
            {
                $this->called = true;
                return $definition;
            }

            public function extract(InputInterface $input, OutputInterface $output, ?Map $data = new Map()): Map
            {
                // TODO: Implement extract() method.
            }
        };
    }
}

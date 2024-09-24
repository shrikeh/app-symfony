<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action\Configurator;

use Ds\Set;
use Shrikeh\SymfonyApp\Console\Action\ConfigurableAction;
use Shrikeh\SymfonyApp\Console\Action\Configurator;
use Shrikeh\SymfonyApp\Console\Input;
use Symfony\Component\Console\Input\InputDefinition;

final readonly class InputConfigurator implements Configurator
{
    /** @var Set<Input>  */
    private Set $inputs;

    public function __construct(Input ...$inputs)
    {
        $this->inputs = new Set(...$inputs);
    }

    public function configure(ConfigurableAction $action): ConfigurableAction
    {
        $action->setDefinition($this->addInputs($action->getDefinition()));

        return $action;
    }

    private function addInputs(InputDefinition $inputDefinition): InputDefinition
    {
        foreach ($this->inputs as $input) {
            $inputDefinition = $input->add($inputDefinition);
        }

        return $inputDefinition;
    }
}

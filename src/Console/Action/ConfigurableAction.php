<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action;

use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputOption;

interface ConfigurableAction
{
    public function getDefinition(): InputDefinition;

    /**
     * @param array<InputOption|InputArgument>|InputDefinition $definition
     * @return ConfigurableAction
     */
    public function setDefinition(array|InputDefinition $definition): static;
}

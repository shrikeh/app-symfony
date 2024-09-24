<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action;

use Symfony\Component\Console\Input\InputDefinition;

interface ConfigurableAction
{
    public function getDefinition(): InputDefinition;

    public function setDefinition(InputDefinition $definition);
}

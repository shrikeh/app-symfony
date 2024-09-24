<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Console\Action;

use Shrikeh\SymfonyApp\Console\Action;

interface Configurator
{
    public function configure(ConfigurableAction $action): ConfigurableAction;
}

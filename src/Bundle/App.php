<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bundle;

use Shrikeh\SymfonyApp\Bundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class App extends AbstractBundle
{
    public function getContainerExtension(): AppExtension
    {
        return new AppExtension();
    }
}

<?php

declare(strict_types=1);

namespace RpHaven\App\Bundle;

use RpHaven\App\Bundle\DependencyInjection\AppExtension;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

final class App extends AbstractBundle
{
    public function getContainerExtension(): AppExtension
    {
        return new AppExtension();
    }
}
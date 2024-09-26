<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Tests\Utils\Path;

final class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    #[\Override] public function getProjectDir(): string
    {
        return Path::appDir();
    }

    #[\Override] public function getCacheDir(): string
    {
        return sprintf('%s/var', Path::buildDir());
    }

    #[\Override] public function getLogDir(): string
    {
        return sprintf('%s/logs', Path::buildDir());
    }
}

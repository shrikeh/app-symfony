<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

final class AppExtension extends Extension
{
    /**
     * @codeCoverageIgnore
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(dirname(__DIR__, 3) . '/config')
        );

        $loader->load('app-common.yaml');
    }
}

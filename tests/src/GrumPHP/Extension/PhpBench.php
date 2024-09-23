<?php

declare(strict_types=1);

namespace Tests\GrumPHP\Extension;

use GrumPHP\Extension\ExtensionInterface;
use Tests\GrumPHP\Tasks\PhpBenchTask;

final readonly class PhpBench implements ExtensionInterface
{
    public function imports(): iterable
    {
        yield dirname(__DIR__) . '/resources/phpbench.yaml';
    }
}

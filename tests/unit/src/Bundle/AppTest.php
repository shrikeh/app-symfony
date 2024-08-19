<?php

declare(strict_types=1);

namespace Tests\Unit\Bundle;

use Shrikeh\SymfonyApp\Bundle\App;
use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Bundle\DependencyInjection\AppExtension;

final class AppTest extends TestCase
{
    public function testItReturnsAnAppExtension(): void
    {
        $bundle = new App();

        $this->assertInstanceOf(AppExtension::class, $bundle->getContainerExtension());
    }
}

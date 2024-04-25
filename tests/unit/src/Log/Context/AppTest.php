<?php

declare(strict_types=1);

namespace Tests\Unit\Log\Context;

use PHPUnit\Framework\TestCase;
use RpHaven\App\Log\Context\App;

final class AppTest extends TestCase
{
    public function testItReturnsAString(): void
    {
        $commandHandlerContext = App::COMMAND_HANDLER;

        $this->assertSame($commandHandlerContext->value, $commandHandlerContext->toString());
    }
}

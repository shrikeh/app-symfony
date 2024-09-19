<?php

declare(strict_types=1);

namespace Tests\Unit\Logger\Traits;

use Shrikeh\App\Log\Context;
use Shrikeh\App\Log\Context\App;
use Shrikeh\App\Logger\Exception\NoContextsPassed;
use Shrikeh\App\Logger\Traits\ContextualLogger;
use PHPUnit\Framework\TestCase;

final class ContextualLoggerTest extends TestCase
{
    public function testItCanContextualize(): void
    {
        $class = new class () {
            use ContextualLogger {
                contextualize as public;
            }
        };

        $contextualizer = new $class();

        $contexts = $contextualizer->contextualize(
            App::COMMAND_HANDLER,
            App::COMMAND_HANDLER,
            App::QUERY_HANDLER
        )
        ;
        $this->assertSame([
            App::COMMAND_HANDLER->value,
            App::QUERY_HANDLER->value,
        ], $contexts);
    }

    public function testItThrpwsAnExceptionIfNoContextsPassed(): void
    {
        $class = new class () {
            use ContextualLogger {
                contextualize as public;
            }
        };

        $contextualizer = new $class();

        $this->expectException(NoContextsPassed::class);
        $this->expectExceptionMessage(NoContextsPassed::MSG);
        $contextualizer->contextualize();
    }
}

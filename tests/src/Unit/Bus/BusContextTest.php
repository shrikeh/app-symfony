<?php

declare(strict_types=1);

namespace Tests\Unit\Bus;

use Shrikeh\App\Bus\BusContext;
use PHPUnit\Framework\TestCase;

final class BusContextTest extends TestCase
{
    /**
     * @return void
     * @covers \Shrikeh\App\Bus\BusContext::toString
     */
    public function testItReturnsAString(): void
    {
        foreach (BusContext::cases() as $case) {
            $this->assertSame($case->value, $case->toString());
        }
    }
}

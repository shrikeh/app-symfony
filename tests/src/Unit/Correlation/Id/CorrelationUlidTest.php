<?php

declare(strict_types=1);

namespace Tests\Unit\Correlation\Id;

use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Correlation\Id\CorrelationUlid;
use Symfony\Component\Uid\Ulid;

final class CorrelationUlidTest extends TestCase
{
    public function testItCanBeCreatedFromBinaryString(): void
    {
        $ulid = new Ulid();

        $correlationId = CorrelationUlid::fromBinary($ulid->toBinary());
        $this->assertSame($ulid->toBinary(), $correlationId->toBinary());
    }

    public function testItBehavesAsRfc4122(): void
    {
        $ulid = new Ulid();

        $correlationId = CorrelationUlid::fromRfc4122($ulid->toRfc4122());
        $this->assertSame($ulid->toRfc4122(), $correlationId->toString());
        $this->assertSame($ulid->toRfc4122(), (string) $correlationId);
    }

    public function testItReturnsTheDateTime(): void
    {
        $ulid = new Ulid();
        $correlationId = CorrelationUlid::fromString($ulid->toBase32());

        $this->assertEquals($ulid->getDateTime(), $correlationId->getDateTime());
    }
}

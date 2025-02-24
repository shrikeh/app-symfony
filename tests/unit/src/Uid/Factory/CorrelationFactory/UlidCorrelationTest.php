<?php

/*
 * This file is part of Barney's Symfony skeleton for Domain-Driven Design
 *
 * (c) Barney Hanlon <symfony@shrikeh.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Tests\Unit\Uid\Factory\CorrelationFactory;

use Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory\UlidCorrelation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Uid\Ulid;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class UlidCorrelationTest extends TestCase
{
    public function testItReturnsACorrelation(): void
    {
        $correlation = (new UlidCorrelation())->correlation();
        $ulid = Ulid::fromString($correlation->correlationId->toString());
        $this->assertSame(
            $ulid->getDateTime()->format('U.u'),
            $correlation->dateTime->format('U.u'),
        );
    }
}

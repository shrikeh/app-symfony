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

namespace Tests\Unit\Bus\Middleware\CorrelatedMessage;

use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\App\Message\Query;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use PHPUnit\Framework\TestCase;
use Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory;
use Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory\UlidCorrelation;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\DelayStamp;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class HandledEnvelopeTest extends TestCase
{
    use ProphecyTrait;

    private CorrelationFactory $correlationFactory;
    protected function setUp(): void
    {
        $this->correlationFactory = new UlidCorrelation();
    }

    public function testItHandlesEnvelopes(): void
    {
        $handlerName = 'foo';
        $correlation = $this->correlationFactory->correlation();
        $query = $this->correlatedMessage()->withCorrelation($correlation);
        $result = $this->correlatedResult();

        $handledStamp = new HandledStamp($result, $handlerName);

        $delayed = [
            new DelayStamp(1),
            new DelayStamp(40),
        ];

        $stamps = array_merge($delayed, [$handledStamp]);
        $envelope = new Envelope($query, $stamps);
        $handledEnvelope = new HandledEnvelope();
        $updatedEnvelope = $handledEnvelope->update($envelope, $handledStamp);

        $this->assertSame($delayed, $updatedEnvelope->all()[DelayStamp::class]);
        $this->assertSame(
            $correlation->correlationId,
            $updatedEnvelope->last(HandledStamp::class)->getResult()->correlated()->correlationId,
        );
    }

    private function correlatedMessage(): Query&Correlated
    {
        return new class () implements Query, Correlated {
            use WithCorrelation;
        };
    }

    private function correlatedResult(): Result&Correlated
    {
        return new class () implements Result, Correlated {
            use WithCorrelation;
        };
    }
}

<?php

declare(strict_types=1);

namespace Tests\Unit\Cqrs\Traits;

use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlation\Traits\WithCorrelation;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory\UlidCorrelation;
use Shrikeh\SymfonyApp\Cqrs\Traits\ShouldCorrelate;
use PHPUnit\Framework\TestCase;

final class ShouldCorrelateTest extends TestCase
{
    use ProphecyTrait;

    public function testItReturnsFalseIfTheMessageIsNotCorrelated(): void
    {
        $message = $this->prophesize(Message::class)->reveal();

        $trait = new class () {
            use ShouldCorrelate {
                shouldCorrelate as public;
            }
        };

        $this->assertFalse($trait->shouldCorrelate($message));
    }

    public function testItReturnsFalseIfTheMessageIsCorrelatedAndHasACorrelation(): void
    {
        $message = new class () implements Message, Message\Correlated {
            use WithCorrelation;
        };

        $message = $message->withCorrelation((new UlidCorrelation())->correlation());

        $trait = new class () {
            use ShouldCorrelate {
                shouldCorrelate as public;
            }
        };

        $this->assertFalse($trait->shouldCorrelate($message));
    }

    public function testItReturnsTrueIfTheMessageIsCorrelatedAndHasNoCorrelation(): void
    {
        $message = new class () implements Message, Message\Correlated {
            use WithCorrelation;
        };

        $trait = new class () {
            use ShouldCorrelate {
                shouldCorrelate as public;
            }
        };

        $this->assertTrue($trait->shouldCorrelate($message));
    }
}

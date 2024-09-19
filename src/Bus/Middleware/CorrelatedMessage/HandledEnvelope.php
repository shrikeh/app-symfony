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

namespace Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage;

use Generator;
use Shrikeh\App\Message\Correlation;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Stamp\StampInterface;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class HandledEnvelope
{
    public function update(Envelope $envelope, HandledStamp $handledStamp): Envelope
    {
        $stamps = array_map(
            static function (StampInterface $stamp) use ($envelope, $handledStamp): StampInterface {
                if ($stamp === $handledStamp) {
                    /** @var Correlation $correlation */
                    $correlation = $envelope->getMessage()->correlated();
                    $stamp = new HandledStamp(
                        $handledStamp->getResult()->withCorrelation($correlation->update()),
                        $handledStamp->getHandlerName(),
                    );
                }

                return $stamp;
            },
            iterator_to_array($this->stamps($envelope->all()))
        );

        return new Envelope($envelope->getMessage(), $stamps);
    }

    /**
     * @param iterable<array<StampInterface>|StampInterface> $stamps
     * @return Generator<StampInterface>
     */
    private function stamps(iterable $stamps): Generator
    {
        foreach ($stamps as $stamp) {
            if (is_array($stamp)) {
                yield from $this->stamps($stamp);
            } else {
                yield $stamp;
            }
        }
    }
}

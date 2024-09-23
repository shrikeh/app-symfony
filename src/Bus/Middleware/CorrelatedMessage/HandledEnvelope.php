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
use Shrikeh\App\Message;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Correlation;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope\StampHandler;
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
        /** @var Message&Correlated $message */
        $message = $envelope->getMessage();

        /** @var array<array-key, StampInterface> $stamps */
        $stamps = array_map(
            new StampHandler($message->correlated(), $handledStamp),
            iterator_to_array($this->stamps($envelope->all()))
        );

        return new Envelope($message, $stamps);
    }

    /**
     * @param array<array-key, mixed> $stamps
     * @return Generator<StampInterface>
     */
    private function stamps(iterable $stamps): Generator
    {
        /** @var array<StampInterface>|StampInterface $stamp */
        foreach ($stamps as $stamp) {
            if (is_array($stamp)) {
                yield from $this->stamps($stamp);
            } else {
                yield $stamp;
            }
        }
    }
}

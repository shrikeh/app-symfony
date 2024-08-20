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

namespace Shrikeh\SymfonyApp\Bus\Middleware;

use Shrikeh\SymfonyApp\Bus\Middleware\CorrelatedMessage\HandledEnvelope;
use Psr\Log\LoggerInterface;
use Shrikeh\App\Message\Correlated;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Middleware\MiddlewareInterface;
use Symfony\Component\Messenger\Middleware\StackInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class CorrelatedMessage implements MiddlewareInterface
{
    public function __construct(
        private HandledEnvelope $handledEnvelope,
        private LoggerInterface $logger,
    ) {
    }

    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        $handled = $envelope->last(HandledStamp::class);
        $result = $handled->getResult();
        if ($result instanceof Correlated && !$result->hasCorrelation()) {
            $this->logger->debug(sprintf('Adding Correlation to result %s', get_class($result)));
            $envelope = $this->handledEnvelope->update($envelope, $handled);
        }

        return $envelope;
    }
}

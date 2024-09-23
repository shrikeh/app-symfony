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

use Shrikeh\App\Message\Result;
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
    public const string MSG_DEBUG = 'Adding Correlation to Result %s';
    public function __construct(
        private HandledEnvelope $handledEnvelope,
        private LoggerInterface $logger,
    ) {
    }

    /**
     * @param Envelope $envelope
     * @param StackInterface $stack
     * @return Envelope
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function handle(Envelope $envelope, StackInterface $stack): Envelope
    {
        if ($handled = $envelope->last(HandledStamp::class)) {
            /** @var Result $result */
            $result = $handled->getResult();

            if ($this->shouldCorrelate($result)) {
                $this->logger->debug(sprintf(self::MSG_DEBUG, get_class($result)));
                $envelope = $this->handledEnvelope->update($envelope, $handled);
            }
        }

        return $envelope;
    }

/**
 * @param mixed $result
 * @return bool
 * @psalm-assert-if-true Correlated $result
 */
    private function shouldCorrelate(mixed $result): bool
    {
        return ($result instanceof Correlated && !$result->hasCorrelation());
    }
}

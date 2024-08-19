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

namespace Shrikeh\SymfonyApp\Bus\Decorator\Traits;

use Shrikeh\App\Log;
use Shrikeh\App\Message\Correlated;
use Shrikeh\SymfonyApp\Bus\BusContext;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
trait CorrelationLog
{
    public const string LOG_FORMAT_DATETIME = 'Y-m-d H:i:s.u';

    public const string MSG_CORRELATION_START = 'Beginning handling of message: %s (time: %s)';
    public const string MSG_CORRELATION_END = 'Ending handling of message: %s (time: %s)';
    private readonly Log $log;


    private function correlationStart(Correlated $correlated): void
    {
        $correlation = $correlated->correlated();
        $this->log->info(sprintf(
            self::MSG_CORRELATION_START,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(self::LOG_FORMAT_DATETIME),
        ), BusContext::MESSAGE_START);
    }

    private function correlationEnd(Correlated $correlated): void
    {
        $correlation = $correlated->correlated();
        $this->log->info(sprintf(
            self::MSG_CORRELATION_END,
            $correlation->correlationId->toString(),
            $correlation->dateTime->format(self::LOG_FORMAT_DATETIME),
        ), BusContext::MESSAGE_END);
    }
}

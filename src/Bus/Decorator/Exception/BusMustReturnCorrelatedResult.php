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

namespace Shrikeh\SymfonyApp\Bus\Decorator\Exception;

use RuntimeException;
use Shrikeh\App\Command\CommandBus;
use Shrikeh\SymfonyApp\Exception\ExceptionMessage;
use Shrikeh\App\Query\QueryBus;
use Shrikeh\App\Message\Result;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class BusMustReturnCorrelatedResult extends RuntimeException implements DecoratorException
{
    public const ExceptionMessage MSG = ExceptionMessage::RESULT_NOT_CORRELATED;

    public function __construct(public readonly QueryBus|CommandBus $bus, public readonly Result $result)
    {
        parent::__construct(self::MSG->message(
            get_class($this->bus),
            get_class($this->result),
        ));
    }
}

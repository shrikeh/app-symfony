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
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Bus\Exception\ExceptionMessage;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final class CorrelatedResultWasUncorrelated extends RuntimeException implements DecoratorException
{
    public const ExceptionMessage MSG = ExceptionMessage::CORRELATED_RESULT_UNCORRELATED;

    public function __construct(public readonly Result&Correlated $result)
    {
        parent::__construct(self::MSG->message(get_class($this->result)));
    }
}

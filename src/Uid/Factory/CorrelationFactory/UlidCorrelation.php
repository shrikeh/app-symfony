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

namespace Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory;


use Shrikeh\App\Message\Correlation;
use Shrikeh\SymfonyApp\Uid\Factory\CorrelationFactory;
use Shrikeh\SymfonyApp\Uid\Id\Ulid\CorrelationUlid;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
final readonly class UlidCorrelation implements CorrelationFactory
{
    public function correlation(): Correlation
    {
        $correlationId = CorrelationUlid::init();

        return new Correlation(
            $correlationId,
            $correlationId->getDateTime(),
        );
    }
}

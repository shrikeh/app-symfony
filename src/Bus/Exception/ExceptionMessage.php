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

namespace Shrikeh\SymfonyApp\Bus\Exception;

use Shrikeh\App\Exception\AppExceptionMessage;
use Shrikeh\SymfonyApp\Exception\Traits\Message;

/**
 * @author Barney Hanlon <symfony@shrikeh.net>
 */
enum ExceptionMessage: string implements AppExceptionMessage
{
    use Message;

    case ERROR_HANDLING_COMMAND = 'Error handling command %s: %s';
    case ERROR_HANDLING_QUERY = 'Error handling query %s: %s';

    case MESSAGE_NO_RESULT = 'Message %s failed to return a valid result';

    case RESULT_NOT_CORRELATED = <<<'EOF'
Inner bus of type %s is expected to return a Correlated Result, type %s returned.
EOF;
    case CORRELATED_RESULT_UNCORRELATED = 'Result of type %s was expected to have a correlation but it does not.';

    case CORRELATION_MISMATCH = 'Correlation mismatch: CQRS correlation %s, but Result correlation %s';

    case CORRELATED_MESSAGE_UNCORRELATED = 'Message of type %s was expected to have a correlation but it does not.';
}

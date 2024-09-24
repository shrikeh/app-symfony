<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Exception;

use Shrikeh\App\Exception\AppExceptionMessage;
use Shrikeh\SymfonyApp\Exception\Traits\Message;

enum ExceptionMessage: string implements AppExceptionMessage
{
    use Message;

    case UNSUPPORTED_RESULT = 'Result type %s is not supported by ResultResponseFactory %s';
}

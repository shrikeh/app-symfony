<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus;

use Shrikeh\App\Log\Context;

enum BusContext: string implements Context
{
    case MESSAGE_START = 'message_start';
    case MESSAGE_END = 'message_end';

    public function toString(): string
    {
        return $this->value;
    }
}

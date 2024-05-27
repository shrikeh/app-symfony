<?php

declare(strict_types=1);

namespace Shrikeh\App\Bus;

use Shrikeh\App\Message;
use Shrikeh\App\Message\Result;

interface MessageBus
{
    public function message(Message $message): ?Result;
}

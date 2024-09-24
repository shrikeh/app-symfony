<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Bus\Exception;

use RuntimeException;
use Shrikeh\App\Message;

final class MessageBusReturnedNoResult extends RuntimeException implements SymfonyMessageBusException
{
    public const ExceptionMessage MSG = ExceptionMessage::MESSAGE_NO_RESULT;
    public function __construct(public readonly Message $cqrs, public readonly mixed $result)
    {
        parent::__construct(self::MSG->message(get_class($cqrs), (string) $this->result));
    }
}

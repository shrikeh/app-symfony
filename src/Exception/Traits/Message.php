<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Exception\Traits;

trait Message
{
    public function message(string ...$args): string
    {
        return sprintf($this->value, ...$args);
    }
}

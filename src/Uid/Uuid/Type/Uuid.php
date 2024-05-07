<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Type;

use RpHaven\Uid\Uid\Type;

enum Uuid: int implements Type
{
    public const NAME = 'uuid';

    case VERSION_5 = 5;
    case VERSION_6 = 6;

    case VERSION_7 = 7;
    #[\Override] public function name(): string
    {
        return self::NAME;
    }

    #[\Override] public function version(): ?string
    {
        return (string) $this->value;
    }
}
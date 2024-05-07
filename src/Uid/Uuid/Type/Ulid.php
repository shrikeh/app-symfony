<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Type;

use RpHaven\Uid\Uid\Type;

enum Ulid: string implements Type
{
    case DEFAULT = 'ulid';

    #[\Override] public function name(): string
    {
        return $this->value;
    }

    #[\Override] public function version(): ?string
    {
        return 'default';
    }
}
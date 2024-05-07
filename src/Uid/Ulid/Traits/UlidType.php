<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Ulid\Traits;

use RpHaven\App\Uid\Uuid\Type\Ulid;

trait UlidType
{
    public function type(): Ulid
    {
        return Ulid::DEFAULT;
    }
}
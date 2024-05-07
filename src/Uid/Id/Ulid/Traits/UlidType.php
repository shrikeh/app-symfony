<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Ulid\Traits;

use RpHaven\App\Uid\Id\Uuid\Type\Ulid;

trait UlidType
{
    public function type(): Ulid
    {
        return Ulid::DEFAULT;
    }
}
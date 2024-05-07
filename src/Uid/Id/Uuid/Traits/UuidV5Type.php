<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid\Traits;

use RpHaven\App\Uid\Id\Uuid\Type\Uuid;

trait UuidV5Type
{
    public function type(): Uuid
    {
        return Uuid::VERSION_5;
    }
}
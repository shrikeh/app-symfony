<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid\Traits;

use RpHaven\App\Uid\Id\Uuid\Type\Uuid;

trait UuidV6Type
{
    public function type(): Uuid
    {
        return Uuid::VERSION_6;
    }
}
<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Traits;

use RpHaven\App\Uid\Uuid\Type\Uuid;

trait UuidV6Type
{
    public function type(): Uuid
    {
        return Uuid::VERSION_6;
    }
}
<?php

declare(strict_types=1);

namespace RpHaven\App\Logger\Traits;

use RpHaven\App\Log\Level;

trait LevelLogger
{
    private function levelize(Level $level): string
    {
        return $level->toString();
    }
}
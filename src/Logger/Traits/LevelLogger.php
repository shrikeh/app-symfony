<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Logger\Traits;

use Shrikeh\App\Log\Level;

trait LevelLogger
{
    private function levelize(Level $level): string
    {
        return $level->toString();
    }
}

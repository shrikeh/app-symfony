<?php

declare(strict_types=1);

namespace Shrikeh\App\Logger\Traits;

use Shrikeh\App\Log\Context;
use Shrikeh\App\Logger\Exception\NoContextsPassed;

trait ContextualLogger
{
    /**
     * @param Context ...$contexts
     * @return array<string>
     * @throws NoContextsPassed If no Contexts have been passed.
     */
    private function contextualize(Context ...$contexts): array
    {
        if (!$contexts) {
            throw new NoContextsPassed();
        }

        return array_values(array_unique(array_map(static function (Context $context): string {
            return $context->toString();
        }, $contexts)));
    }
}

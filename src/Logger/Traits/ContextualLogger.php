<?php

declare(strict_types=1);

namespace RpHaven\App\Logger\Traits;

use RpHaven\App\Log\Context;
use RpHaven\App\Logger\Exception\NoContextsPassed;

trait ContextualLogger
{
    /**
     * @param Context ...$contexts
     * @return array
     * @throws NoContextsPassed If no Contexts have been passed.
     */
    private function contextualize(Context ...$contexts): array
    {
        if (!$contexts) {
            throw new NoContextsPassed();
        }

        return array_map(static function (Context $context): string {
            return $context->toString();
        }, $contexts);
    }
}
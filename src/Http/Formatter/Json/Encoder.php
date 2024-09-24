<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Formatter\Json;

interface Encoder
{
    public function encode(mixed $data): string;
}

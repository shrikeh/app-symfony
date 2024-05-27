<?php

declare(strict_types=1);

namespace Shrikeh\App\Uid\Id\Traits\Ulid;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

trait BinaryUlid
{

    public static function fromBinary(string $bytes): self
    {
        return new self(Ulid::fromBinary($bytes));
    }

    public function toBinary(): string
    {
        return $this->uid()->toBinary();
    }

    abstract private function uid(): AbstractUid;
}

<?php

declare(strict_types=1);

namespace Shrikeh\App\Uid\Id\Traits\Ulid;

use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Ulid;

trait Rfc4122Uid
{

    public static function fromRfc4122(string $uid): self
    {
        return new self(Ulid::fromRfc4122($uid));
    }

    public function __toString(): string
    {
        return $this->toString();
    }

    public function toString(): string
    {
        return $this->uid()->toRfc4122();
    }

    abstract private function uid(): AbstractUid;
}

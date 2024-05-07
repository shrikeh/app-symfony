<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Traits;

use RpHaven\Games\Interface\Uid;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;

trait Rfc4122Uid
{

    public static function fromRfc4122(string $uid): self
    {
        return new self(Uuid::fromRfc4122($uid));
    }

    public function toString(): string
    {
        return $this->uid()->toRfc4122();
    }

    abstract private function uid(): AbstractUid;
}

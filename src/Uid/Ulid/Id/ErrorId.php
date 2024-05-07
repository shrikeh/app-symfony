<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Ulid\Id;

use DateTimeImmutable;
use DateTimeInterface;
use RpHaven\App\Uid\Traits\BinaryUid;
use RpHaven\App\Uid\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Ulid\Traits\UlidType;
use RpHaven\Uid\Traits\ToString;
use RpHaven\Uid\Uid;
use RpHaven\Uid\Uuid\Traits\BinaryUuid;
use Symfony\Component\Uid\Ulid;

final readonly class ErrorId implements Uid
{
    use ToString;
    use BinaryUid;
    use Rfc4122Uid;
    use UlidType;

    public static function init(DateTimeInterface $dateTime = new DateTimeImmutable()): self
    {
        return new self(Ulid::fromString(Ulid::generate($dateTime)));
    }

    private function __construct(public Ulid $ulid)
    {

    }

    public function toString(): string
    {
        return $this->ulid->toRfc4122();
    }

    private function uid(): Ulid
    {
        return $this->ulid;
    }
}
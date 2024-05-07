<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Ulid\Id;

use DateTimeImmutable;
use DateTimeInterface;
use RpHaven\App\Uid\Traits\BinaryUid;
use RpHaven\App\Uid\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Ulid\Traits\UlidType;
use RpHaven\Uid\Id\CorrelationId;
use RpHaven\Uid\Traits\ToString;
use Symfony\Component\Uid\Ulid;

final readonly class CorrelationUlid implements CorrelationId
{
    use UlidType;
    use BinaryUid;
    use Rfc4122Uid;
    use ToString;
    public static function init(DateTimeInterface $dateTime = new DateTimeImmutable()): self
    {
        return self::fromString(Ulid::generate($dateTime));
    }

    public static function fromString(string $uid): self
    {
        return new self(Ulid::fromString($uid));
    }

    private function __construct(public Ulid $uid)
    {

    }

    public function getDateTime(): DateTimeImmutable
    {
        return $this->uid()->getDateTime();
    }

    private function uid(): Ulid
    {
        return $this->uid;
    }
}
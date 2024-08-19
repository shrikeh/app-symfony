<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Uid\Id\Ulid;

use DateTimeImmutable;
use DateTimeInterface;
use Shrikeh\App\Message\Correlation\CorrelationId;
use Shrikeh\SymfonyApp\Uid\Id\Traits\Ulid\BinaryUlid;
use Shrikeh\SymfonyApp\Uid\Id\Traits\Ulid\Rfc4122Uid;
use Symfony\Component\Uid\Ulid;

final readonly class CorrelationUlid implements CorrelationId
{
    use BinaryUlid;
    use Rfc4122Uid;
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

    public function matches(CorrelationId $correlationId): bool
    {
        return $this->uid()->equals($correlationId->toString());
    }
}

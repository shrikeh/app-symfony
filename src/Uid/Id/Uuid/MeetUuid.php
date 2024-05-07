<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid;

use DateTimeImmutable;
use RpHaven\App\Uid\Id\Traits\BinaryUid;
use RpHaven\App\Uid\Id\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Id\Uuid\Traits\UuidV6Type;
use RpHaven\Uid\Id\BranchId;
use RpHaven\Uid\Id\MeetId;
use RpHaven\Uid\Traits\ToString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;


final readonly class MeetUuid implements MeetId
{
    use BinaryUid;
    use Rfc4122Uid;
    use UuidV6Type;
    use ToString;

    public static function create(BranchId $branchId, DateTimeImmutable $start): self
    {
        return new self(UuidV6::fromString(UuidV6::generate($start, Uuid::fromString($branchId->toString()))));
    }

    private function __construct(private UuidV6 $uid)
    {
    }

    private function uid(): UuidV6
    {
        return $this->uid;
    }
}

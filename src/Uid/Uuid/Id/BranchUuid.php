<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Id;

use Psr\Http\Message\UriInterface;
use RpHaven\App\Uid\Traits\BinaryUid;
use RpHaven\App\Uid\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Uuid\Oid;
use RpHaven\App\Uid\Uuid\Traits\UuidV5Type;
use RpHaven\Uid\Id\BranchId;
use RpHaven\Uid\Traits\ToString;
use Symfony\Component\Uid\UuidV5;

final readonly class BranchUuid implements BranchId
{
    use BinaryUid;
    use Rfc4122Uid;
    use UuidV5Type;
    use ToString;

    public const Oid OID = Oid::BRANCH;

    public static function create(UriInterface $uri): self
    {
        return new self(UuidV5::v5(self::OID->namespace(), (string) $uri));
    }

    private function __construct(private UuidV5 $uid)
    {
    }

    private function uid(): UuidV5
    {
        return $this->uid;
    }
}

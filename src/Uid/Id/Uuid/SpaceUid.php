<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid;


use Psr\Http\Message\UriInterface;
use RpHaven\App\Uid\Id\Traits\BinaryUid;
use RpHaven\App\Uid\Id\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Id\Uuid\Traits\UuidV6Type;
use RpHaven\App\Uid\Uuid\Id\UuidV5;
use RpHaven\Uid\Id\SpaceId;
use RpHaven\Uid\Traits\ToString;

final readonly class SpaceUid implements SpaceId
{
    use BinaryUid;
    use Rfc4122Uid;
    use UuidV6Type;
    use ToString;

    public const OID = Oid::SPACE;

    public static function create(UriInterface $space): self
    {
        return new self(UuidV5::v5(
            self::OID->namespace(),
            sprintf('%s:%s', $space->type()->value, $space->uri)
        ));
    }

    private function __construct(private UuidV5 $uid)
    {
    }

    private function uid(): UuidV5
    {
        return $this->uid;
    }
}

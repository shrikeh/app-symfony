<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid;

use DateTimeInterface;
use RpHaven\App\Uid\Id\Traits\BinaryUid;
use RpHaven\App\Uid\Id\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Id\Uuid\Exception\NodeMismatch;
use RpHaven\App\Uid\Id\Uuid\Traits\UuidV6Type;
use RpHaven\Uid\Id\MemberId;
use RpHaven\Uid\Traits\ToString;
use Symfony\Component\Uid\UuidV6;

final readonly class MemberUuid implements MemberId
{
    use BinaryUid;
    use Rfc4122Uid;
    use UuidV6Type;
    use ToString;

    public const Oid OID = Oid::MEMBER;

    public static function create(DateTimeInterface $registration): self
    {
        return new self(UuidV6::fromString(UuidV6::generate($registration, self::OID->namespace())));
    }

    private function __construct(private UuidV6 $uid)
    {
        $node = self::OID->node();
        if ($this->uid->getNode() !== $node) {
            throw new NodeMismatch($uid, $node);
        }
    }

    private function uid(): UuidV6
    {
        return $this->uid;
    }
}
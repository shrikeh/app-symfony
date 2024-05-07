<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Factory;

use DateTimeInterface;
use RpHaven\App\Uid\Uuid\Id\MemberUuid;
use RpHaven\Uid\Id\MemberId;
use RpHaven\Uid\UidFactory;

final readonly class MemberUid implements UidFactory
{

    public function member(DateTimeInterface $registration): MemberId
    {
        return MemberUuid::create($registration);
    }

    public function binary(string $bytes): MemberId
    {
        return MemberUuid::fromBinary($bytes);
    }
}
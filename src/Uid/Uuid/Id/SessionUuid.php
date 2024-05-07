<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Id;

use DateTimeImmutable;
use RpHaven\App\Uid\Uuid\Traits\BinaryUid;
use RpHaven\App\Uid\Uuid\Traits\Rfc4122Uid;
use RpHaven\App\Uid\Uuid\Traits\UuidV6Type;
use RpHaven\Uid\Id\GameId;
use RpHaven\Uid\Id\MeetId;
use RpHaven\Uid\Id\SessionId;
use RpHaven\Uid\Traits\ToString;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Uid\UuidV6;

final readonly class SessionUuid implements SessionId
{
    use BinaryUid;
    use Rfc4122Uid;
    use UuidV6Type;
    use ToString;

    public static function create(GameId $gameId, MeetId $meetId, DateTimeImmutable $start): self
    {
        $node = Uuid::v5(
            Uuid::fromBinary($gameId->toBinary()),
            $meetId->toString(),
        );

        return new self(UuidV6::fromString(UuidV6::generate($start, $node)));
    }

    public static function fromBinary(string $bytes): self
    {
        return new self(UuidV6::fromBinary($bytes));
    }

    private function __construct(private UuidV6 $sessionId)
    {
    }

    private function uid(): UuidV6
    {
        return $this->sessionId;
    }
}

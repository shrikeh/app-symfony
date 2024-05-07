<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Factory;

use DateTimeImmutable;
use RpHaven\Uid\Id\GameId;
use RpHaven\Uid\Id\MeetId;
use RpHaven\Uid\Id\SessionId;
use RpHaven\Uid\Uuid\Id\SessionUuid;

final class SessionUid
{
    public function session(GameId $gameId, MeetId $meetId, DateTimeImmutable $start): SessionId
    {
        return SessionUuid::create($gameId, $meetId, $start);
    }
}
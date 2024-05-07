<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Factory;

use DateTimeImmutable;
use RpHaven\App\Uid\Id\Uuid\SessionUuid;
use RpHaven\Uid\Id\GameId;
use RpHaven\Uid\Id\MeetId;
use RpHaven\Uid\Id\SessionId;


final class SessionUid
{
    public function session(GameId $gameId, MeetId $meetId, DateTimeImmutable $start): SessionId
    {
        return SessionUuid::create($gameId, $meetId, $start);
    }
}
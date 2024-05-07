<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Factory;

use Psr\Http\Message\UriInterface;
use RpHaven\Uid\Id\BranchId;
use RpHaven\Uid\UidFactory;
use RpHaven\Uid\Uuid\Id\BranchUuid;

final readonly class BranchUid implements UidFactory
{
    public function branch(UriInterface $uri): BranchId
    {
        return BranchUuid::create($uri);
    }

    public function binary(string $bytes): BranchId
    {
        return BranchUuid::fromBinary($bytes);
    }
}
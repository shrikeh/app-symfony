<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid;

use Nyholm\Psr7\Uri;
use RpHaven\App\Uid\Id\Uuid\Oid\Store;
use RpHaven\App\Uid\Id\Uuid\Oid\Traits\NamespaceTrait;
use Symfony\Component\Uid\Uuid;

enum Oid: string
{
    use NamespaceTrait;
    public const string BASE_URI = 'https://rpHaven.co.uk/%s';

    case BRANCH = 'branch';
    case GAME = 'game';
    case SESSION = 'session';

    case MEMBER = 'member';

    case MEET = 'meet';
    case SPACE = 'space';

    case TABLE = 'table';

    case TOKEN = 'token';

    case WALLET = 'wallet';



    public function node(): string
    {
        return substr($this->namespace()->toRfc4122(), 24);
    }

    public static function baseUri(): string
    {
        return self::BASE_URI;
    }
}

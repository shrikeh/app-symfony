<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid;

use Nyholm\Psr7\Uri;
use RpHaven\App\Uid\Uuid\Oid\Store;
use Symfony\Component\Uid\Uuid;

enum Oid: string
{
    public const BASE_URI = 'https://RpHaven.co.uk/%s';

    case BRANCH = 'branch';
    case GAME = 'game';
    case SESSION = 'session';

    case MEMBER = 'member';

    case MEET = 'meet';
    case SPACE = 'space';

    case TABLE = 'table';

    case TOKEN = 'token';

    case WALLET = 'wallet';

    public function namespace(): Uuid
    {
        if (!$namespace = Store::get($this)) {
            $oid = $this->oid();
            $uriNamespace = Uuid::fromString(Uuid::NAMESPACE_URL);
            $namespace = Uuid::v5($uriNamespace, (string) $oid);
            Store::storeNamespace($this, $namespace);
        }

        return $namespace;
    }

    public function node(): string
    {
        return substr($this->namespace()->toRfc4122(), 24);
    }

    private function oid(): Uri
    {
        return new Uri(sprintf(self::BASE_URI, $this->value));
    }
}

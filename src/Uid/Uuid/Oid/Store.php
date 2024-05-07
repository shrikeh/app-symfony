<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Uuid\Oid;

use Ds\Map;
use RpHaven\App\Uid\Uuid\Oid;
use Symfony\Component\Uid\UuidV5;

final class Store
{
    private static Map $namespaces;

    public static function get(Oid $oid): ?UuidV5
    {
            return self::getStorage()->get($oid, null);
    }

    public static function storeNamespace(Oid $oid, UuidV5 $namespace): void
    {
        self::getStorage()->put($oid, $namespace);
    }

    private static function getStorage(): Map
    {
        if (!isset(self::$namespaces)) {
            self::$namespaces = new Map();
        }

        return self::$namespaces;
    }
}
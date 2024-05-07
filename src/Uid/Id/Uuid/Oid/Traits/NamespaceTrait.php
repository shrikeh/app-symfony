<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid\Oid\Traits;

use Nyholm\Psr7\Uri;
use RpHaven\App\Uid\Id\Uuid\Oid\Store;
use Symfony\Component\Uid\Uuid;

trait NamespaceTrait
{
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

    private function oid(): Uri
    {
        return new Uri(sprintf(static::baseUri(), $this->value));
    }

    abstract public static function baseUri(): string;
}
<?php

declare(strict_types=1);

namespace RpHaven\App\Uid\Id\Uuid\Exception;

use InvalidArgumentException;
use Symfony\Component\Uid\UuidV6;

final class NodeMismatch extends InvalidArgumentException implements IdException
{

    public function __construct(public readonly UuidV6 $uuid, public readonly string $node)
    {
        parent::__construct(sprintf(
            'UUID %s is expected to have node %s, but instead has %s',
            $uuid->toRfc4122(),
            $node,
            $uuid->getNode(),
        ));
    }
}
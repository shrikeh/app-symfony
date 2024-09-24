<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs;

use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Query;

interface QueryFactory
{
    public function build(ServerRequestInterface $request): Query;
}

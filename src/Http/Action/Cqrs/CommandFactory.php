<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs;

use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Command;

interface CommandFactory
{
    public function build(ServerRequestInterface $request): Command;
}

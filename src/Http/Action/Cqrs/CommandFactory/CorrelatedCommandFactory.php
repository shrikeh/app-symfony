<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs\CommandFactory;

use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Command;
use Shrikeh\App\Message\Correlated;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\CommandFactory;

interface CorrelatedCommandFactory extends CommandFactory
{
    public function build(ServerRequestInterface $request): Command&Correlated;
}

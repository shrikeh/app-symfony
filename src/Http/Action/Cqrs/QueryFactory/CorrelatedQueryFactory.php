<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory;

use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory;

interface CorrelatedQueryFactory extends QueryFactory
{
    public function build(ServerRequestInterface $request): Query&Correlated;
}

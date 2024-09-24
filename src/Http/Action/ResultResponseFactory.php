<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Result;

interface ResultResponseFactory
{
    public function response(Result $result, ?ServerRequestInterface $request): ResponseInterface;
}

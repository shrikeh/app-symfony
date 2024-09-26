<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator;

use Psr\Http\Message\ServerRequestInterface;
use Shrikeh\App\Message\Correlated;
use Shrikeh\App\Message\Query;
use Shrikeh\SymfonyApp\Correlation\CorrelationFactory;
use Shrikeh\SymfonyApp\Cqrs\Traits\ShouldCorrelate;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\CorrelatedQueryFactory;
use Shrikeh\SymfonyApp\Http\Action\Cqrs\QueryFactory\Decorator\CorrelatedQuery\Exception\QueryNotCorrelatable;

final readonly class CorrelatedQuery implements CorrelatedQueryFactory
{
    use ShouldCorrelate;

    public function __construct(
        private QueryFactory $inner,
        private CorrelationFactory $correlationFactory
    ) {
    }

    public function build(ServerRequestInterface $request): Query&Correlated
    {
        $query = $this->inner->build($request);
        if (!$query instanceof Correlated) {
            throw new QueryNotCorrelatable($query);
        }
        /** @psalm-assert-if-true Correlated */
        if ($this->shouldCorrelate($query)) {
            /** @var Query&Correlated $query */
            $query = $query->withCorrelation($this->correlationFactory->correlation());
        }

        return $query;
    }
}

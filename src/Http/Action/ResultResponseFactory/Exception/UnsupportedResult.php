<?php

declare(strict_types=1);

namespace Shrikeh\SymfonyApp\Http\Action\ResultResponseFactory\Exception;

use RuntimeException;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Http\Action\ResultResponseFactory;
use Shrikeh\SymfonyApp\Http\Exception\ExceptionMessage;

final class UnsupportedResult extends RuntimeException implements ResultResponseFactoryException
{
    public const ExceptionMessage MSG = ExceptionMessage::UNSUPPORTED_RESULT;

    public function __construct(
        public readonly Result $result,
        public readonly ResultResponseFactory $resultResponseFactory
    ) {
        parent::__construct(self::MSG->message(
            get_class($this->result),
            get_class($this->resultResponseFactory)
        ));
    }
}

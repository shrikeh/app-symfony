<?php

declare(strict_types=1);

namespace Tests\Unit\Http\Action\ResultResponseFactory\Exception;

use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Shrikeh\App\Message\Result;
use Shrikeh\SymfonyApp\Http\Action\ResultResponseFactory;
use Shrikeh\SymfonyApp\Http\Action\ResultResponseFactory\Exception\UnsupportedResult;
use Shrikeh\SymfonyApp\Http\Exception\ExceptionMessage;

final class UnsupportedResultTest extends TestCase
{
    use ProphecyTrait;

    public function testItUsesTheNameInTheMessage(): void
    {
        $result = $this->prophesize(Result::class)->reveal();
        $resultResponseFactory = $this->prophesize(ResultResponseFactory::class)->reveal();

        $exception = new UnsupportedResult($result, $resultResponseFactory);
        $this->assertSame(
            ExceptionMessage::UNSUPPORTED_RESULT->message(
                get_class($result),
                get_class($resultResponseFactory)
            ),
            $exception->getMessage()
        );
    }
}

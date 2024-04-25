<?php

declare(strict_types=1);

namespace Tests\Unit\Logger;

use Prophecy\PhpUnit\ProphecyTrait;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use RpHaven\App\Log\Context\App;
use RpHaven\App\Log\Level\Psr3;
use RpHaven\App\Logger\Exception\NoContextsPassed;
use RpHaven\App\Logger\Psr3AppLogger;
use PHPUnit\Framework\TestCase;
use RpHaven\App\Logger\Traits\ContextualLogger;

final class Psr3AppLoggerTest extends TestCase
{

    use ProphecyTrait;
    use ContextualLogger;
    public function testItCanLog(): void
    {
        $message = 'foo-bar-baz';

        $contexts = [
            App::COMMAND_HANDLER,
            App::QUERY_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::ALERT, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->log(Psr3::ALERT, $message, ...$contexts);
    }

    public function testItCanDLogDebugLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::DEBUG, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->debug($message, ...$contexts);
    }

    public function testItCanDLogInfoLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::INFO, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->info($message, ...$contexts);
    }

    public function testItCanDLogNoticeLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::NOTICE, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->notice($message, ...$contexts);
    }

    public function testItCanDLogWarningLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::WARNING, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->warning($message, ...$contexts);
    }

    public function testItCanDLogErrorLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::ERROR, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->error($message, ...$contexts);
    }

    public function testItCanDLogCriticalLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::CRITICAL, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->critical($message, ...$contexts);
    }

    public function testItCanDLogAlertLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::ALERT, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->alert($message, ...$contexts);
    }

    public function testItCanDLogEmergencytLevel(): void
    {
        $message = 'bar-baz-bing';

        $contexts = [
            App::COMMAND_HANDLER,
        ];

        $psr3Logger = $this->prophesize(LoggerInterface::class);

        $psr3Logger->log(LogLevel::EMERGENCY, $message, $this->contextualize(...$contexts))->shouldBeCalledOnce();

        $appLogger = new Psr3AppLogger($psr3Logger->reveal());
        $appLogger->emergency($message, ...$contexts);
    }

    public function testItThrowsAnExceptionWithNoContexts(): void
    {
        $this->expectExceptionObject(new NoContextsPassed());

        $appLogger = new Psr3AppLogger($this->prophesize(LoggerInterface::class)->reveal());

        $appLogger->log(Psr3::DEBUG, 'foo');
    }
}

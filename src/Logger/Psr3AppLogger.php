<?php

declare(strict_types=1);

namespace RpHaven\App\Logger;

use Psr\Log\LoggerInterface;
use RpHaven\App\Log;
use RpHaven\App\Log\Context;
use RpHaven\App\Log\Level;
use RpHaven\App\Log\Level\Psr3;

final readonly class Psr3AppLogger implements Log
{

    use Traits\ContextualLogger;
    use Traits\LevelLogger;

    public function __construct(private LoggerInterface $logger)
    {

    }

    public function emergency(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::EMERGENCY, $message, ...$contexts);
    }

    public function alert(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::ALERT, $message, ...$contexts);
    }

    public function critical(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::CRITICAL, $message, ...$contexts);
    }

    public function error(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::ERROR, $message, ...$contexts);
    }

    public function warning(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::WARNING, $message, ...$contexts);
    }

    public function notice(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::NOTICE, $message, ...$contexts);
    }

    public function info(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::INFO, $message, ...$contexts);
    }

    public function debug(\Stringable|string $message, Context ...$contexts): void
    {
        $this->log(Psr3::DEBUG, $message, ...$contexts);
    }

    public function log(Level $level, \Stringable|string $message, Context ...$contexts): void
    {
        $this->logger->log($this->levelize($level), $message, $this->contextualize(...$contexts));
    }
}
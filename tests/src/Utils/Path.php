<?php

declare(strict_types=1);

namespace Tests\Utils;

final readonly class Path
{
    public static function rootDir(): string
    {
        return dirname(__DIR__, 3);
    }

    public static function vendorDir(): string
    {
        return sprintf('%s/vendor', self::rootDir());
    }

    public static function buildDir(): string
    {
        return sprintf('%s/build', self::rootDir());
    }

    public static function testsDir(): string
    {
        return sprintf('%s/tests', self::rootDir());
    }

    public static function appDir(): string
    {
        return sprintf('%s/app', self::testsDir());
    }
}

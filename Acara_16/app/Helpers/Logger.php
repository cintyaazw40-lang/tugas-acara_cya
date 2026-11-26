<?php

class Logger
{
    private static function path(): string
    {
        return __DIR__ . '/../../storage/logs/app.log';
    }

    public static function error(string $context, Throwable $e): void
    {
        $dir = dirname(self::path());
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        $line = date('Y-m-d H:i:s')
            . ' - [' . $context . '] '
            . get_class($e) . ': ' . $e->getMessage()
            . PHP_EOL;

        error_log($line, 3, self::path());
    }
}
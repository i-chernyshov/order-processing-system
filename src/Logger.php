<?php


namespace OrderProcessing;


use Interfaces\ILogger;

class Logger implements ILogger
{
    /**
     * @param string $message
     */
    public static function log(string $message, string $level = 'log'): void
    {
        $dirName = dirname(__DIR__, 1) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR;
        if (!is_dir($dirName)) mkdir($dirName, 0777, true);
        $path = $dirName . "$level.txt";
        file_put_contents($path, $message . PHP_EOL, is_file($path) ? FILE_APPEND : 0);
    }
}

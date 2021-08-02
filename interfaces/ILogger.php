<?php


namespace Interfaces;


interface ILogger
{
    public static function log(string $message, string $level = 'log'): void;
}
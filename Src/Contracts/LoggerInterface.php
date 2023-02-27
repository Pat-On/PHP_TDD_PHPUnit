<?php

namespace App\Contracts;


interface LoggerInterface
{
    // (debug, info, notice, warning, error, critical, alert, emergency).
    // php-fig.org/psr/psr-3/

    public function emergency(string $message, array $context = []);
    public function alert(string $message, array $context = []);
    public function critical(string $message, array $context = []);
    public function error(string $message, array $context = []);
    public function warning(string $message, array $context = []);
    public function info(string $message, array $context = []);
    public function debug(string $message, array $context = []);
    public function log(string $level, string $message, array $context = []);
}

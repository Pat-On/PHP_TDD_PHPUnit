<?php

declare(strict_types=1);


require_once __DIR__ . '/vendor/autoload.php';
require_once(__DIR__ . "/Src/Exception/exception.php");

// $config = \App\Helpers\Config::get('app');

// var_dump($config);
// echo __DIR__ ;


$logger = new \App\Logger\Logger();

$logger->log(\App\Logger\LogLevel::EMERGENCY, "Test no level", ['exception' => 'blahj']);

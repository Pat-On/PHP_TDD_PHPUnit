<?php
declare(strict_types = 1);

require_once __DIR__ .'/vendor/autoload.php';

// $config = \App\Helpers\Config::get('app');

// var_dump($config);
// echo __DIR__ ;


$application = new \App\Helpers\App();

echo $application->getServerTime()->format('D-M-Y') . PHP_EOL;
echo $application->getLogPath() . PHP_EOL;
echo $application->getEnvironment() . PHP_EOL;
echo $application->isDebugMode() . PHP_EOL;

if($application->isRunningFromConsole()) {
    echo "FROM CONSOLE";

} else {
    echo "FROM BROWSER";
}
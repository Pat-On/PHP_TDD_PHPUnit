<?php


namespace App\Logger;

use App\Contracts\LoggerInterface;
use App\Helpers\App;
use App\Exception\InvalidLogLevelArgument;
use ReflectionClass;


class Logger implements LoggerInterface
{

    /**
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function emergency(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::EMERGENCY, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function alert(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::ALERT, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function critical(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::CRITICAL, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function error(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::ERROR, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function warning(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::WARNING, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function info(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::INFO, $message, $context);
    }

    /**
     *
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function debug(string $message, array $context = array())
    {
        $this->addRecord(LogLevel::DEBUG, $message, $context);
    }

    /**
     *
     * @param string $level
     * @param string $message
     * @param array $context
     * @return mixed
     */
    public function log(string $level, string $message, array $context = array())
    {
        $object = new ReflectionClass(LogLevel::class);

        if (!in_array($level, $object->getConstants())) {
            $validLevelsArray = $object->getConstants();
            throw new InvalidLogLevelArgument($level, $validLevelsArray);
        }

        $this->addRecord($level, $message, $context);
    }


    private function addRecord(string $level, string $message, array $context = [])
    {
        $application = new App;
        $date = $application->getServerTime()->format('Y-m-d H:i:s');
        $logPath = $application->getLogPath();
        $env = $application->getEnvironment();
        $details = sprintf(
            '%s - Level: %s - Message %s - Context: %s',
            $date,
            $level,
            $message,
            json_encode($context)
        ) . PHP_EOL;


        $fileName = sprintf("%s/%s-%s.log", $logPath, $env, $date('j.n.Y'));
        file_put_contents($fileName, $details, FILE_APPEND);
    }
}

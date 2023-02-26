<?php

declare(strict_types=1);

namespace App\Exception;

use App\Helpers\App;
use Throwable, ErrorException;

//overwriting the native exceptions handler in php
class ExceptionHandler
{
    public function handler(Throwable $exception): void
    {
        // what kind of the error we want to show base on the env

        $application = new App;


        if ($application->isDebugMode()) {
            var_dump($exception);
        } else {
            echo "This should not have happened, please try again";
        }

        exit;
    }
    // Converting Warnings and Notices to Exceptions to handle it all in one place 
    // https://www.php.net/manual/en/function.error-reporting.php
    public function convertWarningsAndNoticesToException($severity, $message, $file, $line)
    {
        throw new ErrorException($severity, $severity, $message, $file, $line);
    }
}

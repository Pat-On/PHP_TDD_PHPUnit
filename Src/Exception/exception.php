<?php


declare(strict_types=1);

use App\Exception\ExceptionHandler;

set_error_handler([new ExceptionHandler(), 'convertWarningsAndNoticesToException']);
set_exception_handler([new ExceptionHandler(), 'handler']);

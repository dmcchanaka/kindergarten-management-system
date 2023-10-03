<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Exception;

trait ExceptionLogTrait
{
    protected function logException(Exception $e)
    {
        Log::channel('KGMS_ERROR_LOGGER')->error("{$e->getMessage()} in {$e->getFile()} (Line: {$e->getLine()})\r\n");
    }
}

<?php

namespace Core;

class Helpers{

    public static function coreErrorLog(string $error_msg, int $error_level = E_ERROR)
    {
        $timestamp = date("Y-m-d H:i:s");

        $error_levels = [
            E_ERROR => "ERROR",
            E_WARNING => "WARNING",
        ];

        if (is_array($error_msg)) {
            $error_msg = json_encode($error_msg, JSON_PRETTY_PRINT);
        }

        $logEntry = "[{$timestamp}] [{$error_levels[$error_level]}] {$error_msg}" . PHP_EOL;

        error_log($logEntry, 3, ROOT_DIR . "/logs/core_error.log");
    }

}
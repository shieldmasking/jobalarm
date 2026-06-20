<?php

/**
 * log short summary.
 *
 * log description.
 *
 * @version 1.0
 * @author setzo_000
 */

class Log {
    public static function add($message, $category = 0, $severity = 0) {
        $logData = Array(
            'message' => Config::get('db')->filter($message),
            'logDate' => date('Y-m-d H:i:s'),
            'severity' => $severity,
            'category' => $category
        );
        try {
            Config::get('db')->insert('log', $logData);
        }
        catch (Exception $e) {
            file_put_contents('/log/syslog.txt', print_r($logData, true) . " - Error: " . $e->getMessage(), FILE_APPEND);
        }    
    }
}

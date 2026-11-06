<?php
class Logger {
    public static function log($message, $level = 'INFO') {
        $logDir = __DIR__ . '/../../storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true);
        }

        $message = preg_replace('/[\r\n]+/', ' ', (string)$message);   // cegah log forging
        $logFile = $logDir . '/app.log';
        $timestamp = date('Y-m-d H:i:s');
        $formattedMessage = "[{$timestamp}] [{$level}]: {$message}" . PHP_EOL;

        file_put_contents($logFile, $formattedMessage, FILE_APPEND);
    }
}
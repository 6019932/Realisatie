<?php

declare(strict_types=1);

session_start();

$config = require __DIR__ . '/config.php';

if (($config['app']['debug'] ?? false) === true) {
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', '1');
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', '0');
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_STRICT);
}

require_once __DIR__ . '/autoload.php';
require_once __DIR__ . '/helpers.php';

set_exception_handler(static function (Throwable $e) use ($config): void {
    $debug = ($config['app']['debug'] ?? false) === true;

    http_response_code(500);
    header('Content-Type: text/html; charset=utf-8');

    if ($debug) {
        echo '<h1>Unhandled exception</h1>';
        echo '<pre>' . htmlspecialchars((string)$e, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8') . '</pre>';
        return;
    }

    echo '<h1>Er ging iets mis.</h1>';
});


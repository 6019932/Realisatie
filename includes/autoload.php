<?php

declare(strict_types=1);

spl_autoload_register(static function (string $class): void {
    // Verwacht classes in de vorm: App\Something\ClassName
    $prefix = 'App\\';
    $baseDir = __DIR__ . '/../classes/';

    if (!str_starts_with($class, $prefix)) {
        return;
    }

    $relative = substr($class, strlen($prefix));
    $file = $baseDir . str_replace('\\', '/', $relative) . '.php';

    if (is_file($file)) {
        require_once $file;
    }
});


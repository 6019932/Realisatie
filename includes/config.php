<?php

declare(strict_types=1);

// Vul dit aan met jouw lokale MySQL settings (XAMPP default: root / geen password).
return [
    'db' => [
        'host' => '127.0.0.1',
        'port' => 3306,
        'name' => 'realisatie',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        // Zet dit op false in productie
        'debug' => true,
    ],
];


<?php

declare(strict_types=1);

use App\Database;
use App\Boek;
use App\Advertentie;
use App\Gebruiker;

require_once __DIR__ . '/bootstrap.php';

$config = require __DIR__ . '/config.php';

$db = new Database($config['db']);
$users = new Gebruiker($db);
$books = new Boek($db);
$ads = new Advertentie($db);


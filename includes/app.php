<?php

declare(strict_types=1);

use App\Database;
use App\Book;
use App\Advertisement;
use App\User;

require_once __DIR__ . '/bootstrap.php';

$config = require __DIR__ . '/config.php';

$db = new Database($config['db']);
$users = new User($db);
$books = new Book($db);
$ads = new Advertisement($db);


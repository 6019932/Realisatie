<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

unset($_SESSION['user']);
session_regenerate_id(true);

redirect('/Realisatie/public/index.php');


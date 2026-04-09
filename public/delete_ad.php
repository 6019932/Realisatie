<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('/Realisatie/public/ads.php');
}

$id = (int)($_POST['id'] ?? 0);
if ($id > 0) {
    try {
        $ads->deleteAd($id, (int)$cu['id']);
    } catch (Throwable $e) {
        // Silent fail: keep UX simple for this assignment step.
    }
}

redirect('/Realisatie/public/ads.php');


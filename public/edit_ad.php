<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    redirect('/Realisatie/public/ads.php');
}

$ad = $ads->getAdById($id);
if ($ad === null || (int)$ad['gebruiker_id'] !== (int)$cu['id']) {
    redirect('/Realisatie/public/ads.php');
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $status = (string)($_POST['status'] ?? '');

    try {
        $ads->updateAdStatus($id, (int)$cu['id'], $status);
        $ad = $ads->getAdById($id) ?? $ad;
        $success = 'Advertentie bijgewerkt.';
    } catch (Throwable $e) {
        $error = $e->getMessage();
    }
}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Advertentie bewerken</title>
</head>
<body>
    <h1>Advertentie bewerken</h1>

    <?php if ($success): ?>
        <p style="color:green;"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Status</label><br>
            <select name="status" required>
                <?php
                $s = (string)($ad['status'] ?? 'actief');
                foreach (['actief', 'verkocht', 'verwijderd'] as $opt) {
                    $sel = $s === $opt ? 'selected' : '';
                    echo '<option value="' . e($opt) . '" ' . $sel . '>' . e($opt) . '</option>';
                }
                ?>
            </select>
        </div>
        <button type="submit">Opslaan</button>
    </form>

    <p><a href="ads.php">Terug</a></p>
</body>
</html>


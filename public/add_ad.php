<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

$error = null;

try {
    $choices = $ads->getOwnBooksWithoutAd((int)$cu['id']);
} catch (Throwable $e) {
    $choices = [];
    $error = $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $boekId = (int)($_POST['boek_id'] ?? 0);
    $status = (string)($_POST['status'] ?? 'actief');

    try {
        $ads->createAd($boekId, (int)$cu['id'], $status);
        redirect('/Realisatie/public/ads.php');
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
    <title>Advertentie plaatsen</title>
</head>
<body>
    <h1>Advertentie plaatsen</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <?php if ($choices === []): ?>
        <p>Je hebt geen boeken zonder advertentie. Voeg eerst een boek toe of verwijder een bestaande advertentie.</p>
        <p><a href="books.php">Naar boeken</a> | <a href="ads.php">Terug</a></p>
    <?php else: ?>
        <form method="post">
            <div>
                <label>Boek</label><br>
                <select name="boek_id" required>
                    <?php
                    $selected = (int)($_POST['boek_id'] ?? 0);
                    foreach ($choices as $c) {
                        $sel = $selected === (int)$c['id'] ? 'selected' : '';
                        echo '<option value="' . e((string)$c['id']) . '" ' . $sel . '>' . e((string)$c['titel']) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <div>
                <label>Status</label><br>
                <select name="status" required>
                    <?php
                    $s = (string)($_POST['status'] ?? 'actief');
                    foreach (['actief', 'verkocht', 'verwijderd'] as $opt) {
                        $sel = $s === $opt ? 'selected' : '';
                        echo '<option value="' . e($opt) . '" ' . $sel . '>' . e($opt) . '</option>';
                    }
                    ?>
                </select>
            </div>
            <button type="submit">Plaatsen</button>
        </form>

        <p><a href="ads.php">Terug</a></p>
    <?php endif; ?>
</body>
</html>


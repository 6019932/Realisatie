<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

try {
    $allAds = $ads->getAllAds();
} catch (Throwable $e) {
    $allAds = [];
    $error = $e->getMessage();
}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Advertenties</title>
</head>
<body>
    <h1>Advertenties</h1>

    <p>
        <a href="add_ad.php">Advertentie plaatsen</a> |
        <a href="index.php">Home</a>
    </p>

    <?php if (!empty($error ?? null)): ?>
        <p style="color:red;"><?= e((string)$error) ?></p>
    <?php endif; ?>

    <?php if ($allAds === []): ?>
        <p>Geen advertenties gevonden.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Boek</th>
                <th>Prijs</th>
                <th>Eigenaar</th>
                <th>Status</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allAds as $a): ?>
                <tr>
                    <td><?= e((string)$a['id']) ?></td>
                    <td><?= e((string)$a['boek_titel']) ?></td>
                    <td>€ <?= e((string)$a['boek_prijs']) ?></td>
                    <td><?= e((string)$a['eigenaar_naam']) ?></td>
                    <td><?= e((string)$a['status']) ?></td>
                    <td>
                        <?php if ($cu && (int)$a['gebruiker_id'] === (int)$cu['id']): ?>
                            <a href="edit_ad.php?id=<?= e((string)$a['id']) ?>">Bewerken</a>
                            <form method="post" action="delete_ad.php" style="display:inline" onsubmit="return confirm('Advertentie verwijderen?');">
                                <input type="hidden" name="id" value="<?= e((string)$a['id']) ?>">
                                <button type="submit">Verwijderen</button>
                            </form>
                        <?php else: ?>
                            -
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</body>
</html>


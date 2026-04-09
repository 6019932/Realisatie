<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

try {
    $allBooks = $books->getAllBooks();
} catch (Throwable $e) {
    $allBooks = [];
    $error = $e->getMessage();
}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Boeken</title>
</head>
<body>
    <h1>Boeken</h1>

    <p>
        <a href="add_book.php">Boek toevoegen</a> |
        <a href="index.php">Home</a>
    </p>

    <?php if (!empty($error ?? null)): ?>
        <p style="color:red;"><?= e((string)$error) ?></p>
    <?php endif; ?>

    <?php if ($allBooks === []): ?>
        <p>Geen boeken gevonden.</p>
    <?php else: ?>
        <table border="1" cellpadding="6" cellspacing="0">
            <thead>
            <tr>
                <th>ID</th>
                <th>Titel</th>
                <th>Auteur</th>
                <th>Conditie</th>
                <th>Prijs</th>
                <th>Categorie</th>
                <th>Locatie</th>
                <th>Eigenaar</th>
                <th>Acties</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($allBooks as $b): ?>
                <tr>
                    <td><?= e((string)$b['id']) ?></td>
                    <td><?= e((string)$b['titel']) ?></td>
                    <td><?= e((string)$b['auteur']) ?></td>
                    <td><?= e((string)$b['conditie']) ?></td>
                    <td>€ <?= e((string)$b['prijs']) ?></td>
                    <td><?= e((string)$b['categorie']) ?></td>
                    <td><?= e((string)$b['locatie']) ?></td>
                    <td><?= e((string)$b['eigenaar_naam']) ?></td>
                    <td>
                        <?php if ($cu && (int)$b['eigenaar_id'] === (int)$cu['id']): ?>
                            <a href="edit_book.php?id=<?= e((string)$b['id']) ?>">Bewerken</a>
                            <form method="post" action="delete_book.php" style="display:inline" onsubmit="return confirm('Boek verwijderen?');">
                                <input type="hidden" name="id" value="<?= e((string)$b['id']) ?>">
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


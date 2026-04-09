<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'titel' => (string)($_POST['titel'] ?? ''),
        'auteur' => (string)($_POST['auteur'] ?? ''),
        'conditie' => (string)($_POST['conditie'] ?? ''),
        'prijs' => $_POST['prijs'] ?? null,
        'categorie' => (string)($_POST['categorie'] ?? ''),
        'locatie' => (string)($_POST['locatie'] ?? ''),
    ];

    try {
        $books->createBook($data, (int)$cu['id']);
        redirect('/Realisatie/public/books.php');
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
    <title>Boek toevoegen</title>
</head>
<body>
    <h1>Boek toevoegen</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Titel</label><br>
            <input name="titel" value="<?= e((string)($_POST['titel'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Auteur</label><br>
            <input name="auteur" value="<?= e((string)($_POST['auteur'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Conditie</label><br>
            <select name="conditie" required>
                <?php
                $c = (string)($_POST['conditie'] ?? '');
                foreach (['nieuw', 'goed', 'gebruikt'] as $opt) {
                    $sel = $c === $opt ? 'selected' : '';
                    echo '<option value="' . e($opt) . '" ' . $sel . '>' . e($opt) . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <label>Prijs</label><br>
            <input name="prijs" inputmode="decimal" value="<?= e((string)($_POST['prijs'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Categorie</label><br>
            <input name="categorie" value="<?= e((string)($_POST['categorie'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Locatie</label><br>
            <input name="locatie" value="<?= e((string)($_POST['locatie'] ?? '')) ?>" required>
        </div>
        <button type="submit">Opslaan</button>
    </form>

    <p><a href="books.php">Terug</a></p>
</body>
</html>


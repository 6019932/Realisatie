<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

$id = (int)($_GET['id'] ?? 0);
if ($id <= 0) {
    redirect('/Realisatie/public/books.php');
}

$book = $books->getBookById($id);
if ($book === null || (int)$book['eigenaar_id'] !== (int)$cu['id']) {
    redirect('/Realisatie/public/books.php');
}

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fields = [
        'titel' => (string)($_POST['titel'] ?? ''),
        'auteur' => (string)($_POST['auteur'] ?? ''),
        'conditie' => (string)($_POST['conditie'] ?? ''),
        'prijs' => $_POST['prijs'] ?? null,
        'categorie' => (string)($_POST['categorie'] ?? ''),
        'locatie' => (string)($_POST['locatie'] ?? ''),
    ];

    try {
        $books->updateBook($id, (int)$cu['id'], $fields);
        $book = $books->getBookById($id) ?? $book;
        $success = 'Boek bijgewerkt.';
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
    <title>Boek bewerken</title>
</head>
<body>
    <h1>Boek bewerken</h1>

    <?php if ($success): ?>
        <p style="color:green;"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Titel</label><br>
            <input name="titel" value="<?= e((string)($book['titel'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Auteur</label><br>
            <input name="auteur" value="<?= e((string)($book['auteur'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Conditie</label><br>
            <select name="conditie" required>
                <?php
                $c = (string)($book['conditie'] ?? '');
                foreach (['nieuw', 'goed', 'gebruikt'] as $opt) {
                    $sel = $c === $opt ? 'selected' : '';
                    echo '<option value="' . e($opt) . '" ' . $sel . '>' . e($opt) . '</option>';
                }
                ?>
            </select>
        </div>
        <div>
            <label>Prijs</label><br>
            <input name="prijs" inputmode="decimal" value="<?= e((string)($book['prijs'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Categorie</label><br>
            <input name="categorie" value="<?= e((string)($book['categorie'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Locatie</label><br>
            <input name="locatie" value="<?= e((string)($book['locatie'] ?? '')) ?>" required>
        </div>
        <button type="submit">Opslaan</button>
    </form>

    <p><a href="books.php">Terug</a></p>
</body>
</html>


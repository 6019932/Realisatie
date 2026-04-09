<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

require_login();
$cu = current_user();

$dbUser = $cu ? $users->getUserById((int)$cu['id']) : null;
if ($dbUser === null) {
    unset($_SESSION['user']);
    redirect('/Realisatie/public/login.php');
}

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profiel</title>
</head>
<body>
    <h1>Profiel</h1>

    <ul>
        <li><strong>ID</strong>: <?= e((string)$dbUser['id']) ?></li>
        <li><strong>Naam</strong>: <?= e((string)$dbUser['naam']) ?></li>
        <li><strong>E-mail</strong>: <?= e((string)$dbUser['email']) ?></li>
        <li><strong>Rol</strong>: <?= e((string)$dbUser['rol']) ?></li>
    </ul>

    <p><a href="profile_edit.php">Profiel bewerken</a></p>
    <p><a href="logout.php">Uitloggen</a></p>
    <p><a href="index.php">Home</a></p>
</body>
</html>


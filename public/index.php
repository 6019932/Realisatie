<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/bootstrap.php';

$user = current_user();

?>
<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Realisatie</title>
</head>
<body>
    <h1>Realisatie</h1>

    <?php if ($user === null): ?>
        <p>Je bent niet ingelogd.</p>
        <ul>
            <li><a href="register.php">Registreren</a></li>
            <li><a href="login.php">Inloggen</a></li>
        </ul>
    <?php else: ?>
        <p>Welkom, <?= e($user['naam']) ?>.</p>
        <ul>
            <li><a href="profile.php">Profiel bekijken</a></li>
            <li><a href="profile_edit.php">Profiel bewerken</a></li>
            <li><a href="books.php">Boeken</a></li>
            <li><a href="logout.php">Uitloggen</a></li>
        </ul>
    <?php endif; ?>
</body>
</html>


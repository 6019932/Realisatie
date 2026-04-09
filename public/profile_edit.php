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

$error = null;
$success = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = (string)($_POST['naam'] ?? '');
    $email = (string)($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $fields = [
        'naam' => $naam,
        'email' => $email,
    ];
    if ($password !== '') {
        $fields['password'] = $password;
    }

    try {
        $users->updateUser((int)$dbUser['id'], $fields);

        $fresh = $users->getUserById((int)$dbUser['id']);
        if ($fresh === null) {
            throw new RuntimeException('Gebruiker niet gevonden na update.');
        }

        $_SESSION['user'] = [
            'id' => (int)$fresh['id'],
            'naam' => (string)$fresh['naam'],
            'email' => (string)$fresh['email'],
            'rol' => (string)$fresh['rol'],
        ];

        $dbUser = $fresh;
        $success = 'Profiel bijgewerkt.';
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
    <title>Profiel bewerken</title>
</head>
<body>
    <h1>Profiel bewerken</h1>

    <?php if ($success): ?>
        <p style="color:green;"><?= e($success) ?></p>
    <?php endif; ?>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Naam</label><br>
            <input name="naam" value="<?= e((string)$dbUser['naam']) ?>" required>
        </div>
        <div>
            <label>E-mail</label><br>
            <input type="email" name="email" value="<?= e((string)$dbUser['email']) ?>" required>
        </div>
        <div>
            <label>Nieuw wachtwoord (optioneel)</label><br>
            <input type="password" name="password">
        </div>
        <button type="submit">Opslaan</button>
    </form>

    <p><a href="profile.php">Terug naar profiel</a></p>
    <p><a href="index.php">Home</a></p>
</body>
</html>


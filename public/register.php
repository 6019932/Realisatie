<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $naam = (string)($_POST['naam'] ?? '');
    $email = (string)($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    try {
        $id = $users->createUser($naam, $email, $password, 'student');
        $u = $users->getUserById($id);

        if ($u === null) {
            throw new RuntimeException('Registratie gelukt, maar gebruiker niet gevonden.');
        }

        $_SESSION['user'] = [
            'id' => (int)$u['id'],
            'naam' => (string)$u['naam'],
            'email' => (string)$u['email'],
            'rol' => (string)$u['rol'],
        ];

        redirect('/Realisatie/public/profile.php');
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
    <title>Registreren</title>
</head>
<body>
    <h1>Registreren</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>Naam</label><br>
            <input name="naam" value="<?= e((string)($_POST['naam'] ?? '')) ?>" required>
        </div>
        <div>
            <label>E-mail</label><br>
            <input type="email" name="email" value="<?= e((string)($_POST['email'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Wachtwoord</label><br>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Account aanmaken</button>
    </form>

    <p><a href="login.php">Al een account? Inloggen</a></p>
    <p><a href="index.php">Home</a></p>
</body>
</html>


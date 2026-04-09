<?php

declare(strict_types=1);

require_once __DIR__ . '/../includes/app.php';

$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = (string)($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    try {
        $user = $users->authenticate($email, $password);
        if ($user === null) {
            throw new RuntimeException('Onjuiste login gegevens.');
        }

        $_SESSION['user'] = $user;
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
    <title>Inloggen</title>
</head>
<body>
    <h1>Inloggen</h1>

    <?php if ($error): ?>
        <p style="color:red;"><?= e($error) ?></p>
    <?php endif; ?>

    <form method="post">
        <div>
            <label>E-mail</label><br>
            <input type="email" name="email" value="<?= e((string)($_POST['email'] ?? '')) ?>" required>
        </div>
        <div>
            <label>Wachtwoord</label><br>
            <input type="password" name="password" required>
        </div>
        <button type="submit">Inloggen</button>
    </form>

    <p><a href="register.php">Nog geen account? Registreren</a></p>
    <p><a href="index.php">Home</a></p>
</body>
</html>


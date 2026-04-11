<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;
use RuntimeException;

class Gebruiker
{
    protected PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function registreren(string $naam, string $email, string $password, string $rol = 'student'): int
    {
        return $this->createUser($naam, $email, $password, $rol);
    }

    public function inloggen(string $email, string $password): ?array
    {
        return $this->authenticate($email, $password);
    }

    /**
     * @param array{naam?:string,email?:string,password?:string,rol?:string} $fields
     */
    public function profielBewerken(int $id, array $fields): bool
    {
        return $this->updateUser($id, $fields);
    }

    public function wachtwoordHerstellen(string $email, string $newPassword): bool
    {
        $user = $this->getUserByEmail($email);
        if ($user === null) {
            return false;
        }

        return $this->updateUser((int)$user['id'], ['password' => $newPassword]);
    }

    public function createUser(string $naam, string $email, string $password, string $rol = 'student'): int
    {
        $naam = trim($naam);
        $email = trim($email);

        if ($naam === '' || $email === '' || $password === '') {
            throw new InvalidArgumentException('Naam, e-mail en wachtwoord zijn verplicht.');
        }
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Ongeldig e-mailadres.');
        }
        if (!in_array($rol, ['student', 'beheerder'], true)) {
            throw new InvalidArgumentException('Ongeldige rol.');
        }

        $hash = password_hash($password, PASSWORD_DEFAULT);
        if ($hash === false) {
            throw new RuntimeException('Wachtwoord kon niet worden gehasht.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO gebruiker (naam, email, wachtwoord, rol) VALUES (:naam, :email, :wachtwoord, :rol)'
        );

        $stmt->execute([
            ':naam' => $naam,
            ':email' => $email,
            ':wachtwoord' => $hash,
            ':rol' => $rol,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @return array{id:int,naam:string,email:string,wachtwoord:string,rol:string}|null
     */
    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, naam, email, wachtwoord, rol FROM gebruiker WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return array{id:int,naam:string,email:string,wachtwoord:string,rol:string}|null
     */
    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, naam, email, wachtwoord, rol FROM gebruiker WHERE email = :email');
        $stmt->execute([':email' => trim($email)]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array{naam?:string,email?:string,password?:string,rol?:string} $fields
     */
    public function updateUser(int $id, array $fields): bool
    {
        $set = [];
        $params = [':id' => $id];

        if (array_key_exists('naam', $fields)) {
            $naam = trim((string)$fields['naam']);
            if ($naam === '') {
                throw new InvalidArgumentException('Naam mag niet leeg zijn.');
            }
            $set[] = 'naam = :naam';
            $params[':naam'] = $naam;
        }

        if (array_key_exists('email', $fields)) {
            $email = trim((string)$fields['email']);
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException('Ongeldig e-mailadres.');
            }
            $set[] = 'email = :email';
            $params[':email'] = $email;
        }

        if (array_key_exists('rol', $fields)) {
            $rol = (string)$fields['rol'];
            if (!in_array($rol, ['student', 'beheerder'], true)) {
                throw new InvalidArgumentException('Ongeldige rol.');
            }
            $set[] = 'rol = :rol';
            $params[':rol'] = $rol;
        }

        if (array_key_exists('password', $fields)) {
            $password = (string)$fields['password'];
            if ($password === '') {
                throw new InvalidArgumentException('Wachtwoord mag niet leeg zijn.');
            }
            $hash = password_hash($password, PASSWORD_DEFAULT);
            if ($hash === false) {
                throw new RuntimeException('Wachtwoord kon niet worden gehasht.');
            }
            $set[] = 'wachtwoord = :wachtwoord';
            $params[':wachtwoord'] = $hash;
        }

        if ($set === []) {
            throw new InvalidArgumentException('Geen velden om te updaten.');
        }

        $sql = 'UPDATE gebruiker SET ' . implode(', ', $set) . ' WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function deleteUser(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM gebruiker WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    /**
     * @return array{id:int,naam:string,email:string,rol:string}|null
     */
    public function authenticate(string $email, string $password): ?array
    {
        $user = $this->getUserByEmail($email);
        if ($user === null) {
            return null;
        }

        if (!password_verify($password, $user['wachtwoord'])) {
            return null;
        }

        return [
            'id' => (int)$user['id'],
            'naam' => (string)$user['naam'],
            'email' => (string)$user['email'],
            'rol' => (string)$user['rol'],
        ];
    }
}

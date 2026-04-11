<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Bericht
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function berichtVerzenden(string $inhoud, int $verzenderId, int $ontvangerId): int
    {
        $inhoud = trim($inhoud);
        if ($inhoud === '') {
            throw new InvalidArgumentException('Bericht mag niet leeg zijn.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO bericht (inhoud, verzender_id, ontvanger_id)
             VALUES (:inhoud, :verzender_id, :ontvanger_id)'
        );
        $stmt->execute([
            ':inhoud' => $inhoud,
            ':verzender_id' => $verzenderId,
            ':ontvanger_id' => $ontvangerId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function berichtLezen(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, inhoud, verzender_id, ontvanger_id FROM bericht WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }
}

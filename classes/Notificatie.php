<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Notificatie
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function notificatieVerzenden(string $bericht, int $gebruikerId): int
    {
        $bericht = trim($bericht);
        if ($bericht === '') {
            throw new InvalidArgumentException('Notificatiebericht mag niet leeg zijn.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO notificatie (bericht, gelezen, gebruiker_id)
             VALUES (:bericht, 0, :gebruiker_id)'
        );
        $stmt->execute([
            ':bericht' => $bericht,
            ':gebruiker_id' => $gebruikerId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function notificatieLezen(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE notificatie SET gelezen = 1 WHERE id = :id');
        $stmt->execute([':id' => $id]);

        return $stmt->rowCount() > 0;
    }

    public function getNotificatiesVoorGebruiker(int $gebruikerId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, bericht, gelezen, gebruiker_id
             FROM notificatie
             WHERE gebruiker_id = :gebruiker_id
             ORDER BY id DESC'
        );
        $stmt->execute([':gebruiker_id' => $gebruikerId]);

        return $stmt->fetchAll();
    }
}

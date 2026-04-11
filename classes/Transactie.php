<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Transactie
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function transactieStarten(
        int $koperId,
        int $verkoperId,
        int $boekId,
        string $status = 'in behandeling'
    ): int {
        if (!in_array($status, ['in behandeling', 'voltooid', 'geannuleerd'], true)) {
            throw new InvalidArgumentException('Ongeldige transactiestatus.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO transactie (status, koper_id, verkoper_id, boek_id)
             VALUES (:status, :koper_id, :verkoper_id, :boek_id)'
        );
        $stmt->execute([
            ':status' => $status,
            ':koper_id' => $koperId,
            ':verkoper_id' => $verkoperId,
            ':boek_id' => $boekId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function transactieVoltooien(int $id): bool
    {
        $stmt = $this->pdo->prepare('UPDATE transactie SET status = :status WHERE id = :id');
        $stmt->execute([':status' => 'voltooid', ':id' => $id]);

        return $stmt->rowCount() > 0;
    }
}

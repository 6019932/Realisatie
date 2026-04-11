<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Beoordeling
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function beoordelingGeven(int $score, string $recensie, int $beoordelaarId, int $beoordeeldeId): int
    {
        if ($score < 1 || $score > 5) {
            throw new InvalidArgumentException('Score moet tussen 1 en 5 zijn.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO beoordeling (score, recensie, beoordelaar_id, beoordeelde_id)
             VALUES (:score, :recensie, :beoordelaar_id, :beoordeelde_id)'
        );
        $stmt->execute([
            ':score' => $score,
            ':recensie' => trim($recensie),
            ':beoordelaar_id' => $beoordelaarId,
            ':beoordeelde_id' => $beoordeeldeId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }
}

<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Advertentie
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    public function plaatsen(int $boekId, int $gebruikerId, string $status = 'actief'): int
    {
        return $this->createAd($boekId, $gebruikerId, $status);
    }

    public function bewerken(int $adId, int $gebruikerId, string $status): bool
    {
        return $this->updateAdStatus($adId, $gebruikerId, $status);
    }

    public function verwijderen(int $adId, int $gebruikerId): bool
    {
        return $this->deleteAd($adId, $gebruikerId);
    }

    public function createAd(int $boekId, int $gebruikerId, string $status = 'actief'): int
    {
        if ($boekId <= 0) {
            throw new InvalidArgumentException('Ongeldig boek.');
        }
        if ($gebruikerId <= 0) {
            throw new InvalidArgumentException('Ongeldige gebruiker.');
        }
        if (!in_array($status, ['actief', 'verkocht', 'verwijderd'], true)) {
            throw new InvalidArgumentException('Ongeldige status.');
        }

        $stmt = $this->pdo->prepare('SELECT eigenaar_id FROM boek WHERE id = :id');
        $stmt->execute([':id' => $boekId]);
        $row = $stmt->fetch();
        if ($row === false || (int)$row['eigenaar_id'] !== $gebruikerId) {
            throw new InvalidArgumentException('Je kan alleen een advertentie plaatsen voor je eigen boek.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO advertentie (status, boek_id, gebruiker_id) VALUES (:status, :boek_id, :gebruiker_id)'
        );
        $stmt->execute([
            ':status' => $status,
            ':boek_id' => $boekId,
            ':gebruiker_id' => $gebruikerId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @return array{id:int,status:string,boek_id:int,gebruiker_id:int}|null
     */
    public function getAdById(int $id): ?array
    {
        $stmt = $this->pdo->prepare('SELECT id, status, boek_id, gebruiker_id FROM advertentie WHERE id = :id');
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return array<int, array{id:int,status:string,boek_id:int,gebruiker_id:int,boek_titel:string,boek_prijs:string,eigenaar_naam:string}>
     */
    public function getAllAds(): array
    {
        $stmt = $this->pdo->query(
            'SELECT a.id, a.status, a.boek_id, a.gebruiker_id,
                    b.titel AS boek_titel, b.prijs AS boek_prijs,
                    g.naam AS eigenaar_naam
             FROM advertentie a
             JOIN boek b ON b.id = a.boek_id
             JOIN gebruiker g ON g.id = a.gebruiker_id
             ORDER BY a.id DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array{id:int,status:string,boek_id:int,gebruiker_id:int,boek_titel:string,boek_prijs:string}>
     */
    public function getAdsByOwner(int $gebruikerId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT a.id, a.status, a.boek_id, a.gebruiker_id,
                    b.titel AS boek_titel, b.prijs AS boek_prijs
             FROM advertentie a
             JOIN boek b ON b.id = a.boek_id
             WHERE a.gebruiker_id = :gebruiker_id
             ORDER BY a.id DESC'
        );
        $stmt->execute([':gebruiker_id' => $gebruikerId]);

        return $stmt->fetchAll();
    }

    public function updateAdStatus(int $adId, int $gebruikerId, string $status): bool
    {
        if (!in_array($status, ['actief', 'verkocht', 'verwijderd'], true)) {
            throw new InvalidArgumentException('Ongeldige status.');
        }

        $stmt = $this->pdo->prepare(
            'UPDATE advertentie SET status = :status WHERE id = :id AND gebruiker_id = :gebruiker_id'
        );
        $stmt->execute([
            ':status' => $status,
            ':id' => $adId,
            ':gebruiker_id' => $gebruikerId,
        ]);

        return $stmt->rowCount() > 0;
    }

    public function deleteAd(int $adId, int $gebruikerId): bool
    {
        return $this->updateAdStatus($adId, $gebruikerId, 'verwijderd');
    }

    /**
     * @return array<int, array{id:int,titel:string}>
     */
    public function getOwnBooksWithoutAd(int $gebruikerId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT b.id, b.titel
             FROM boek b
             LEFT JOIN advertentie a ON a.boek_id = b.id
             WHERE b.eigenaar_id = :gebruiker_id AND a.id IS NULL
             ORDER BY b.id DESC'
        );
        $stmt->execute([':gebruiker_id' => $gebruikerId]);

        return $stmt->fetchAll();
    }
}

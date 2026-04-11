<?php

declare(strict_types=1);

namespace App;

use InvalidArgumentException;
use PDO;

final class Boek
{
    private PDO $pdo;

    public function __construct(Database $db)
    {
        $this->pdo = $db->pdo();
    }

    /**
     * @param array{titel:string,auteur:string,conditie:string,prijs:mixed,categorie:string,locatie:string} $data
     */
    public function toevoegen(array $data, int $eigenaarId): int
    {
        return $this->createBook($data, $eigenaarId);
    }

    /**
     * @param array{titel?:string,auteur?:string,conditie?:string,prijs?:mixed,categorie?:string,locatie?:string} $fields
     */
    public function bewerken(int $id, int $eigenaarId, array $fields): bool
    {
        return $this->updateBook($id, $eigenaarId, $fields);
    }

    /**
     * @param array{titel:string,auteur:string,conditie:string,prijs:mixed,categorie:string,locatie:string} $data
     */
    public function createBook(array $data, int $eigenaarId): int
    {
        $titel = trim((string)($data['titel'] ?? ''));
        $auteur = trim((string)($data['auteur'] ?? ''));
        $conditie = trim((string)($data['conditie'] ?? ''));
        $categorie = trim((string)($data['categorie'] ?? ''));
        $locatie = trim((string)($data['locatie'] ?? ''));
        $prijsRaw = $data['prijs'] ?? null;

        if ($titel === '' || $auteur === '' || $conditie === '' || $categorie === '' || $locatie === '') {
            throw new InvalidArgumentException('Alle velden zijn verplicht.');
        }
        if (!in_array($conditie, ['nieuw', 'goed', 'gebruikt'], true)) {
            throw new InvalidArgumentException('Ongeldige conditie.');
        }
        if (!is_numeric($prijsRaw)) {
            throw new InvalidArgumentException('Prijs moet een getal zijn.');
        }
        $prijs = (float)$prijsRaw;
        if ($prijs < 0) {
            throw new InvalidArgumentException('Prijs moet positief zijn.');
        }

        $stmt = $this->pdo->prepare(
            'INSERT INTO boek (titel, auteur, conditie, prijs, categorie, locatie, eigenaar_id)
             VALUES (:titel, :auteur, :conditie, :prijs, :categorie, :locatie, :eigenaar_id)'
        );
        $stmt->execute([
            ':titel' => $titel,
            ':auteur' => $auteur,
            ':conditie' => $conditie,
            ':prijs' => number_format($prijs, 2, '.', ''),
            ':categorie' => $categorie,
            ':locatie' => $locatie,
            ':eigenaar_id' => $eigenaarId,
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    /**
     * @return array{id:int,titel:string,auteur:string,conditie:string,prijs:string,categorie:string,locatie:string,eigenaar_id:int}|null
     */
    public function getBookById(int $id): ?array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, titel, auteur, conditie, prijs, categorie, locatie, eigenaar_id
             FROM boek
             WHERE id = :id'
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @return array<int, array{id:int,titel:string,auteur:string,conditie:string,prijs:string,categorie:string,locatie:string,eigenaar_id:int}>
     */
    public function getBooksByOwner(int $eigenaarId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT id, titel, auteur, conditie, prijs, categorie, locatie, eigenaar_id
             FROM boek
             WHERE eigenaar_id = :eigenaar_id
             ORDER BY id DESC'
        );
        $stmt->execute([':eigenaar_id' => $eigenaarId]);

        return $stmt->fetchAll();
    }

    /**
     * @return array<int, array{id:int,titel:string,auteur:string,conditie:string,prijs:string,categorie:string,locatie:string,eigenaar_id:int,eigenaar_naam:string}>
     */
    public function getAllBooks(): array
    {
        $stmt = $this->pdo->query(
            'SELECT b.id, b.titel, b.auteur, b.conditie, b.prijs, b.categorie, b.locatie, b.eigenaar_id, g.naam AS eigenaar_naam
             FROM boek b
             JOIN gebruiker g ON g.id = b.eigenaar_id
             ORDER BY b.id DESC'
        );

        return $stmt->fetchAll();
    }

    /**
     * @param array{titel?:string,auteur?:string,conditie?:string,prijs?:mixed,categorie?:string,locatie?:string} $fields
     */
    public function updateBook(int $id, int $eigenaarId, array $fields): bool
    {
        $existing = $this->getBookById($id);
        if ($existing === null || (int)$existing['eigenaar_id'] !== $eigenaarId) {
            throw new InvalidArgumentException('Boek niet gevonden of geen toegang.');
        }

        $set = [];
        $params = [':id' => $id, ':eigenaar_id' => $eigenaarId];

        if (array_key_exists('titel', $fields)) {
            $titel = trim((string)$fields['titel']);
            if ($titel === '') {
                throw new InvalidArgumentException('Titel is verplicht.');
            }
            $set[] = 'titel = :titel';
            $params[':titel'] = $titel;
        }

        if (array_key_exists('auteur', $fields)) {
            $auteur = trim((string)$fields['auteur']);
            if ($auteur === '') {
                throw new InvalidArgumentException('Auteur is verplicht.');
            }
            $set[] = 'auteur = :auteur';
            $params[':auteur'] = $auteur;
        }

        if (array_key_exists('conditie', $fields)) {
            $conditie = trim((string)$fields['conditie']);
            if (!in_array($conditie, ['nieuw', 'goed', 'gebruikt'], true)) {
                throw new InvalidArgumentException('Ongeldige conditie.');
            }
            $set[] = 'conditie = :conditie';
            $params[':conditie'] = $conditie;
        }

        if (array_key_exists('prijs', $fields)) {
            $prijsRaw = $fields['prijs'];
            if (!is_numeric($prijsRaw)) {
                throw new InvalidArgumentException('Prijs moet een getal zijn.');
            }
            $prijs = (float)$prijsRaw;
            if ($prijs < 0) {
                throw new InvalidArgumentException('Prijs moet positief zijn.');
            }
            $set[] = 'prijs = :prijs';
            $params[':prijs'] = number_format($prijs, 2, '.', '');
        }

        if (array_key_exists('categorie', $fields)) {
            $categorie = trim((string)$fields['categorie']);
            if ($categorie === '') {
                throw new InvalidArgumentException('Categorie is verplicht.');
            }
            $set[] = 'categorie = :categorie';
            $params[':categorie'] = $categorie;
        }

        if (array_key_exists('locatie', $fields)) {
            $locatie = trim((string)$fields['locatie']);
            if ($locatie === '') {
                throw new InvalidArgumentException('Locatie is verplicht.');
            }
            $set[] = 'locatie = :locatie';
            $params[':locatie'] = $locatie;
        }

        if ($set === []) {
            throw new InvalidArgumentException('Geen velden om te updaten.');
        }

        $sql = 'UPDATE boek SET ' . implode(', ', $set) . ' WHERE id = :id AND eigenaar_id = :eigenaar_id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->rowCount() > 0;
    }

    public function deleteBook(int $id, int $eigenaarId): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM boek WHERE id = :id AND eigenaar_id = :eigenaar_id');
        $stmt->execute([':id' => $id, ':eigenaar_id' => $eigenaarId]);

        return $stmt->rowCount() > 0;
    }
}

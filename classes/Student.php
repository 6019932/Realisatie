<?php

declare(strict_types=1);

namespace App;

final class Student extends Gebruiker
{
    /**
     * @param array{titel:string,auteur:string,conditie:string,prijs:mixed,categorie:string,locatie:string} $data
     */
    public function boekenToevoegen(Boek $boek, array $data, int $studentId): int
    {
        return $boek->toevoegen($data, $studentId);
    }

    public function advertentiesPlaatsen(Advertentie $advertentie, int $boekId, int $studentId): int
    {
        return $advertentie->plaatsen($boekId, $studentId);
    }

    public function transactieStarten(Transactie $transactie, int $koperId, int $verkoperId, int $boekId): int
    {
        return $transactie->transactieStarten($koperId, $verkoperId, $boekId);
    }

    public function beoordelingGeven(
        Beoordeling $beoordeling,
        int $score,
        string $recensie,
        int $beoordelaarId,
        int $beoordeeldeId
    ): int {
        return $beoordeling->beoordelingGeven($score, $recensie, $beoordelaarId, $beoordeeldeId);
    }

    public function berichtVerzenden(Bericht $bericht, string $inhoud, int $verzenderId, int $ontvangerId): int
    {
        return $bericht->berichtVerzenden($inhoud, $verzenderId, $ontvangerId);
    }

    public function notificatiesOntvangen(Notificatie $notificatie, int $studentId): array
    {
        return $notificatie->getNotificatiesVoorGebruiker($studentId);
    }
}

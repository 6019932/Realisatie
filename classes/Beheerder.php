<?php

declare(strict_types=1);

namespace App;

final class Beheerder extends Gebruiker
{
    /**
     * @param array{naam?:string,email?:string,password?:string,rol?:string} $fields
     */
    public function gebruikersBeheren(int $gebruikerId, array $fields): bool
    {
        return $this->updateUser($gebruikerId, $fields);
    }

    public function rollenBeheren(int $gebruikerId, string $rol): bool
    {
        return $this->updateUser($gebruikerId, ['rol' => $rol]);
    }

    public function advertentiesModereren(Advertentie $advertentie, int $advertentieId, int $eigenaarId, string $status): bool
    {
        return $advertentie->bewerken($advertentieId, $eigenaarId, $status);
    }
}

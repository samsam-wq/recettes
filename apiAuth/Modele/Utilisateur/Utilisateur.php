<?php

namespace apiAuth\modele\utilisateur;

class Utilisateur {
    private string $login;
    private string $motDePasse;
    private int $groupe;

    public function __construct(
        string $login,
        string $motDePasse,
        int $groupe
    ) {
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->groupe = $groupe;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    public function getGroupe(): int
    {
        return $this->groupe;
    }
}


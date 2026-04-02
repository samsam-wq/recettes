<?php

namespace apiAuth\modele\utilisateur;

class Utilisateur {
    private string $login;
    private string $motDePasse;
    private string $role;

    public function __construct(
        string $login,
        string $motDePasse,
        string $role
    ) {
        $this->login = $login;
        $this->motDePasse = $motDePasse;
        $this->role = $role;
    }

    public function getLogin(): string
    {
        return $this->login;
    }

    public function getMotDePasse(): string
    {
        return $this->motDePasse;
    }

    public function getRole(): string
    {
        return $this->role;
    }
}


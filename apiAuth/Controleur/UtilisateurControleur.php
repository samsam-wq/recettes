<?php

namespace apiAuth\Controleur;

use apiAuth\Modele\utilisateur\Utilisateur;
use apiAuth\Modele\Utilisateur\UtilisateurDAO;

class UtilisateurControleur {
    private static ?UtilisateurControleur $instance = null;
    private readonly UtilisateurDAO $utilisateurs;

    private function __construct() {
        $this->utilisateurs = UtilisateurDAO::getInstance();
    }

    public static function getInstance(): UtilisateurControleur {
        if (self::$instance == null) {
            self::$instance = new UtilisateurControleur();
        }
        return self::$instance;
    }

    public function seConnecter(string $username, string $password): bool|Utilisateur {
        if ($this->utilisateurs->isUserValide($username,$password)){
            return $this->utilisateurs->getUser($username,$password);
        } else {
            return false;
        }
    }
}
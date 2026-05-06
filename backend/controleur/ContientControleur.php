<?php

namespace Backend\Controleur;

use backend\modele\dao\DaoContient;
use backend\modele\Contient;

class ContientControleur {
    private static ?ContientControleur $instance = null;
    private readonly DaoContient $contients;

    private function __construct() {
        $this->contients = DaoContient::getInstance();
    }

    public static function getInstance(): ContientControleur {
        if (self::$instance == null) {
            self::$instance = new ContientControleur();
        }
        return self::$instance;
    }

    public function ajouterContient(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite,
        string $unite
    ):bool{
        return $this->contients->insert(new Contient($Id_Ustensiles,$Id_Recette,$numero,$quantite,$unite)); 
    }

    public function supprimerContient(int $Id_Ustensiles,int $Id_Recette,int $numero):bool{
        return $this->contients->delete(array($Id_Ustensiles, $Id_Recette, $numero));
    }

    public function supprimerContientEtape(int $Id_Recette,int $numero):bool{
        return $this->contients->deleteDeEtape(array($Id_Recette, $numero));
    }

    public function supprimerContientRecette(int $Id_Recette):bool{
        return $this->contients->deleteDeRecette($Id_Recette);
    }

    public function modifierContient(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite,
        string $unite
    ):bool{
        return $this->contients->update(new Contient($Id_Ustensiles,$Id_Recette,$numero,$quantite,$unite));
    }
}
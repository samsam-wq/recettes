<?php

namespace Backend\Controleur;

use backend\modele\dao\DaoUtilise;
use backend\modele\Utilise;

class UtiliseControleur {
    private static ?UtiliseControleur $instance = null;
    private readonly DaoUtilise $utilises;

    private function __construct() {
        $this->utilises = DaoUtilise::getInstance();
    }

    public static function getInstance(): UtiliseControleur {
        if (self::$instance == null) {
            self::$instance = new UtiliseControleur();
        }
        return self::$instance;
    }

    public function ajouterUtilise(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite
    ):bool{
        return $this->utilises->insert(new Utilise($Id_Ustensiles,$Id_Recette,$numero,$quantite)); 
    }

    public function supprimerUtilise(int $Id_Ustensiles,int $Id_Recette,int $numero):bool{
        return $this->utilises->delete(array($Id_Ustensiles, $Id_Recette, $numero));
    }

    public function supprimerUtiliseEtape(int $Id_Recette,int $numero):bool{
        return $this->utilises->deleteDeEtape(array($Id_Recette, $numero));
    }

    public function supprimerUtiliseRecette(int $Id_Recette):bool{
        return $this->utilises->deleteDeRecette($Id_Recette);
    }

    public function modifierUtilise(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite
    ):bool{
        return $this->utilises->update(new Utilise($Id_Ustensiles,$Id_Recette,$numero,$quantite));
    }
}
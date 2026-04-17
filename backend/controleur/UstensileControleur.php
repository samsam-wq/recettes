<?php

namespace backend\controleur;

use backend\modele\dao\DaoUstensile;
use backend\modele\Ustensile;

class UstensileControleur {
    private static ?UstensileControleur $instance = null;
    private readonly DaoUstensile $ustensiles;

    private function __construct() {
        $this->ustensiles = DaoUstensile::getInstance();
    }

    public static function getInstance(): UstensileControleur {
        if (self::$instance == null) {
            self::$instance = new UstensileControleur();
        }
        return self::$instance;
    }

    public function ajouterUstensile(
        string $nom
    ):string{
        return $this->ustensiles->insert(new Ustensile(0,$nom)); 
    }

    public function supprimerUstensile(int $id):bool{
        return $this->ustensiles->delete($id);
    }

    public function tousLesUstensile():array{
        return $this->ustensiles->findAll();
    }

    public function tousLesUstensileDeEtape($id,$numero):array{
        return $this->ustensiles->findByIdEtape(array($id,$numero));
    }

    public function tousLesUstensileDeRecette($id):array{
        return $this->ustensiles->findByIdRecette($id);
    }

    public function lUstensile(int $id):?Ustensile{
        return $this->ustensiles->findById($id);
    }

    public function modifierUstensile(
        int $id,
        string $nom
    ):bool{
        return $this->ustensiles->update(new Ustensile($id,$nom));
    }
}
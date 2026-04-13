<?php

namespace backend\controleur;

use backend\modele\dao\DaoEtape;
use backend\modele\Etape;

class EtapeControleur {
    private static ?EtapeControleur $instance = null;
    private readonly DaoEtape $etapes;

    private function __construct() {
        $this->etapes = DaoEtape::getInstance();
    }

    public static function getInstance(): EtapeControleur {
        if (self::$instance == null) {
            self::$instance = new EtapeControleur();
        }
        return self::$instance;
    }

    public function ajouterEtape(
        string $titre,
        string $contenu,
        int $numero,
        int $Id_Recette
    ):string{
        return $this->etapes->insert(new Etape($titre,$contenu,$numero,$Id_Recette));
    }

    public function supprimerEtape(int $Id_Recette, int $numero):bool{
        return $this->etapes->delete(array($Id_Recette,$numero));
    }

    public function supprimeretapesRecette(int $Id_Recette):bool{
        return $this->etapes->deleteEtapesDeLarecette($Id_Recette);
    }

    public function toutesLesEtapes():?array{
        return $this->etapes->findAll();
    }

    public function letape(int $Id_Recette, int $numero):?Etape{
        return $this->etapes->findById(array($Id_Recette, $numero));
    }

    public function modifierEtape(  
        string $titre,
        string $contenu,
        int $numero,
        int $Id_Recette
    ):bool{
        return $this->etapes->update(new Etape($titre,$contenu,$numero,$Id_Recette));
    }

    public function lesEtapesDuPlat(int $Id_Recette) : array {
        return $this->etapes->findByIdRecette($Id_Recette);
    }

}
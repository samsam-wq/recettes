<?php

namespace Backend\Controleur;

use backend\modele\RecetteCategorie;
use backend\modele\dao\DaoRecette;
use backend\modele\Recette;

class RecetteControleur {
    private static ?RecetteControleur $instance = null;
    private readonly DaoRecette $recettes;

    private function __construct() {
        $this->recettes = DaoRecette::getInstance();
    }

    public static function getInstance(): RecetteControleur {
        if (self::$instance == null) {
            self::$instance = new RecetteControleur();
        }
        return self::$instance;
    }

    public function ajouterRecette(
        string $nom,
        int $duree,
        RecetteCategorie $categorie,
        string $image,
        int $groupe
    ):bool{
        return $this->recettes->insert(new Recette(0,$nom,$duree,$categorie,$image,$groupe)); 
    }

    public function supprimerRecette(int $id):bool{
        return $this->recettes->delete($id);
    }

    public function toutesLesRecettes():?array{
        return $this->recettes->findAll();
    }

    public function toutesLesRecettesDuGroupe(int $groupe):?array{
        return $this->recettes->findByGroupe($groupe);
    }

    public function laRecette(int $Id_recette):?Recette{
        return $this->recettes->findById($Id_recette);
    }

    public function modifierRecette(
        int $Id_recette,
        string $nom,
        int $duree,
        RecetteCategorie $categorie,
        string $image,
        int $groupe
    ):bool{
        return $this->recettes->update(new Recette($Id_recette,$nom,$duree,$categorie,$image,$groupe));
    }

public function filtrerRecettes(int $groupe, ?string $categorie, string|int $duree, ?string $recherche): ?array {
    $lstRecettes = $this->toutesLesRecettesDuGroupe($groupe);
    $retenu = array();

    foreach ($lstRecettes as $recette) {
        $aRetenir = true; 

        if (!empty($categorie) && RecetteCategorie::fromName($categorie) !== null) {
            if ($recette->getCategorie() !== RecetteCategorie::fromName($categorie)) {
                $aRetenir = false;
            }
        }

        if (!empty($duree) && $aRetenir) {
            if ($recette->getDuree() > $duree) {
                $aRetenir = false;
            }
        }

        if (!empty($recherche) && $aRetenir) {
            if (!str_contains(strtolower($recette->getNom()), strtolower($recherche))) {
                $aRetenir = false;
            }
        }

        if ($aRetenir) {
            $retenu[] = $recette;
        }
    }

    return empty($retenu) ? null : $retenu;
}

}
<?php

namespace Backend\Controleur;

use backend\modele\dao\DaoNoter;
use backend\modele\Noter;

class NoterControleur {
    private static ?NoterControleur $instance = null;
    private readonly DaoNoter $notes;

    private function __construct() {
        $this->notes = DaoNoter::getInstance();
    }

    public static function getInstance(): NoterControleur {
        if (self::$instance == null) {
            self::$instance = new NoterControleur();
        }
        return self::$instance;
    }

    public function ajouterNote(
        int $Id_Recette,
        string $login,
        int $note,
        bool $specialite,
        bool $favori
    ):string{
        return $this->notes->insert(new Noter($Id_Recette,$login,$note,$specialite,$favori));
    }

    public function supprimerNote(int $Id_Recette,string $login):bool{
        return $this->notes->delete(array($Id_Recette,$login));
    }

    public function supprimerNotesRecette(int $Id_Recette):bool{
        return $this->notes->deleteNotesDeLarecette($Id_Recette);
    }

    public function toutesLesNotes():?array{
        return $this->notes->findAll();
    }

    public function laNote(int $Id_Recette,string $login):?Noter{
        return $this->notes->findById(array($Id_Recette,$login));
    }

    public function modifierNote(  
        int $Id_Recette,
        string $login,
        int $note,
        bool $specialite,
        bool $favori
    ):bool{
        return $this->notes->update(new Noter($Id_Recette,$login,$note,$specialite,$favori));
    }

    public function lesNotesDuPlat(int $Id_Recette) : array {
        return $this->notes->findByIdRecette($Id_Recette);
    }

}
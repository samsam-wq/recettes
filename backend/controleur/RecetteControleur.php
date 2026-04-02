<?php

namespace Backend\Controleur;

use backend\Modele\Dao\DaoRecette;

class RecetteControleur {
    private static ?RecetteControleur $instance = null;
    private readonly DaoRecette $joueurs;

    private function __construct() {
        $this->joueurs = DaoRecette::getInstance();
    }

    public static function getInstance(): RecetteControleur {
        if (self::$instance == null) {
            self::$instance = new RecetteControleur();
        }
        return self::$instance;
    }



}
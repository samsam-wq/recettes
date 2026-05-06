<?php

namespace backend\controleur;

use backend\modele\dao\DaoIngredient;
use backend\modele\Ingredient;

class IngredientControleur {
    private static ?IngredientControleur $instance = null;
    private readonly DaoIngredient $ingredients;

    private function __construct() {
        $this->ingredients = DaoIngredient::getInstance();
    }

    public static function getInstance(): IngredientControleur {
        if (self::$instance == null) {
            self::$instance = new IngredientControleur();
        }
        return self::$instance;
    }

    public function ajouterIngredient(
        string $nom,
        string $image
    ):string{
        return $this->ingredients->insert(new Ingredient(0,$nom,$image)); 
    }

    public function supprimerIngredient(int $id):bool{
        return $this->ingredients->delete($id);
    }

    public function tousLesIngredient():array{
        return $this->ingredients->findAll();
    }

    public function tousLesIngredientDeEtape($id,$numero):array{
        return $this->ingredients->findByIdEtape(array($id,$numero));
    }

    public function tousLesIngredientDeRecette($id):array{
        return $this->ingredients->findByIdRecette($id);
    }

    public function lIngredient(int $id):?Ingredient{
        return $this->ingredients->findById($id);
    }

    public function modifierIngredient(
        int $id,
        string $nom,
        string $image
    ):bool{
        return $this->ingredients->update(new Ingredient($id,$nom,$image));
    }
}
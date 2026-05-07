<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class IngredientControleur {
    private static ?IngredientControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Ingredient";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
    }

    public static function getInstance(): IngredientControleur {
        if (self::$instance == null) {
            self::$instance = new IngredientControleur();
        }
        return self::$instance;
    }

    public function ajouterIngredient(
        string $nom
    ):array{
        $payload = [
            'nom' => $nom
        ];
        return $this->apiServide->callApi($this->url,"POST",$payload);
    }

    public function supprimerIngredient(int $id):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array(null,null,null,$id));
    }

    public function tousLesIngredient():array{
        return $this->apiServide->callApi($this->url,"GET");
    }

    public function tousLesIngredientDeEtape($id,$numero):array{
        return $this->apiServide->callApi($this->url,"GET",null,array("etape",$id,$numero));
    }

    public function tousLesIngredientDeRecette($id):array{
        return $this->apiServide->callApi($this->url,"GET",null,array("recette",$id));
    }

    public function lIngredient(int $id):array{
        return $this->apiServide->callApi($this->url,"GET",null,array($id));
    }

    public function modifierIngredient(
        int $id,
        string $nom
    ):array{
        $payload = [
            'nom' => $nom
        ];
        return $this->apiServide->callApi($this->url,"PUT",$payload,array(null,null,null,$id));
    }
}
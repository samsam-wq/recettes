<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class ContientControleur {
    private static ?ContientControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Ingredient";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
    }

    public static function getInstance(): ContientControleur {
        if (self::$instance == null) {
            self::$instance = new ContientControleur();
        }
        return self::$instance;
    }

    public function ajouterContient(
        int $Id_Ingredient,
        int $Id_Recette,
        int $numero,
        float $quantite,
        string $unite
    ):array{
        $payload = [
            'Id_Ingredient' => $Id_Ingredient,
            'Id_Recette' => $Id_Recette,
            'numero' => $numero,
            'quantite' => $quantite,
            'unite' => $unite
        ];
        return $this->apiServide->callApi($this->url,"POST",$payload,array("etape"));
    }

    public function supprimerContient(int $Id_Ustensiles,int $Id_Recette,int $numero):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("etape",$Id_Recette,$numero,$Id_Ustensiles));
    }

    public function supprimerContientEtape(int $Id_Recette,int $numero):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("etape",$Id_Recette,$numero,null));
    }

    public function supprimerContientRecette(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("recette",$Id_Recette,null,null));
    }

    public function modifierContient(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite,
        string $unite
    ):array{
        $payload = [
            'quantite' => $quantite,
            'unite' => $unite
        ];
        return $this->apiServide->callApi($this->url,"PUT",$payload,array("etape",$Id_Recette,$numero,$Id_Ustensiles));
    }
}
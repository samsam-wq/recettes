<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class UtiliseControleur {
    private static ?UtiliseControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Ustensile";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
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
    ):array{
        $payload = [
            'Id_Ustensiles' => $Id_Ustensiles,
            'Id_Recette' => $Id_Recette,
            'numero' => $numero,
            'quantite' => $quantite
        ];
        return $this->apiServide->callApi($this->url,"POST",$payload,array("etape"));
    }

    public function supprimerUtilise(int $Id_Ustensiles,int $Id_Recette,int $numero):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("etape",$Id_Recette,$numero,$Id_Ustensiles));
    }

    public function supprimerUtiliseEtape(int $Id_Recette,int $numero):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("etape",$Id_Recette,$numero,null));
    }

    public function supprimerUtiliseRecette(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array("recette",$Id_Recette,null,null));
    }

    public function modifierUtilise(
        int $Id_Ustensiles,
        int $Id_Recette,
        int $numero,
        float $quantite
    ):array{
        $payload = [
            'quantite' => $quantite
        ];
        return $this->apiServide->callApi($this->url,"PUT",$payload,array("etape",$Id_Recette,$numero,$Id_Ustensiles));
    }
}
<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class EtapeControleur {
    private static ?EtapeControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Etape";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
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
    ):array{
        $payload = [
            'Id_Recette' => $Id_Recette ,
            'numero' => $numero ,
            'titre' => $titre ,
            'contenu' => $contenu 
        ];
        return $this->apiServide->callApi($this->url,"POST",$payload);
    }

    public function supprimerEtape(int $Id_Recette, int $numero):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array($Id_Recette,$numero));
    }

    public function supprimeretapesRecette(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array($Id_Recette));
    }

    public function toutesLesEtapes():array{
        return $this->apiServide->callApi($this->url,"GET");
    }

    public function letape(int $Id_Recette, int $numero):array{
        return $this->apiServide->callApi($this->url,"GET",null,array($Id_Recette,$numero));
    }

    public function modifierEtape(  
        string $titre,
        string $contenu,
        int $numero,
        int $Id_Recette
    ):array{
        $payload = [
            'titre' => $titre ,
            'contenu' => $contenu 
        ];
        return $this->apiServide->callApi($this->url,"PUT",$payload,array($Id_Recette,$numero));
    }

    public function lesEtapesDuPlat(int $Id_Recette) : array {
        return $this->apiServide->callApi($this->url,"GET",null,array($Id_Recette));
    }

}
<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class UstensileControleur {
    private static ?UstensileControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Ustensile";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
    }

    public static function getInstance(): UstensileControleur {
        if (self::$instance == null) {
            self::$instance = new UstensileControleur();
        }
        return self::$instance;
    }

    public function ajouterUstensile(
        string $nom
    ):array{
        $payload = [
            'nom' => $nom
        ];
        return $this->apiServide->callApi($this->url,"POST",$payload);
    }

    public function supprimerUstensile(int $id):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array(null,null,null,$id));
    }

    public function tousLesUstensile():array{
        return $this->apiServide->callApi($this->url,"GET");
    }

    public function tousLesUstensileDeEtape($id,$numero):array{
        return $this->apiServide->callApi($this->url,"GET",null,array("etape",$id,$numero));
    }

    public function tousLesUstensileDeRecette($id):array{
        return $this->apiServide->callApi($this->url,"GET",null,array("recette",$id));
    }

    public function lUstensile(int $id):array{
        return $this->apiServide->callApi($this->url,"GET",null,array($id));
    }

    public function modifierUstensile(
        int $id,
        string $nom
    ):array{
        $payload = [
            'nom' => $nom
        ];
        return $this->apiServide->callApi($this->url,"PUT",$payload,array(null,null,null,$id));
    }
}
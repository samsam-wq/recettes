<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class RecetteControleur {
    private static ?RecetteControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Recette";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
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
        string $categorie,
        string $image,
        int $groupe
    ):array{
        $payload = [
            "nom" => $nom,
            "duree" => $duree,
            "categorie" => $categorie,
            "image" => $image,
            "groupe" => $groupe
        ];
        $reponse = $this->apiServide->callApi($this->url,"POST",$payload);
        return $reponse;
    }

    public function supprimerRecette(int $id):array{
        $reponse = $this->apiServide->callApi($this->url,"DELETE",null,array($id));
        return $reponse;
    }

    public function toutesLesRecettesDuGroupe():array{
        return $this->apiServide->callApi($this->url,"GET");
    }

    public function toutesLesRecettes():array{
        return $this->apiServide->callApi($this->url,"GET",null,array("all"));
    }

    public function laRecette(int $Id_recette):array{
        return $this->apiServide->callApi($this->url,"GET",null,array($Id_recette));
    }

    public function modifierRecette(
        int $Id_recette,
        string $nom,
        int $duree,
        string $categorie,
        string $image,
        int $groupe
    ):array{
        $payload = [
            "nom" => $nom,
            "duree" => $duree,
            "categorie" => $categorie,
            "image" => $image,
            "groupe" => $groupe
        ];
        $reponse = $this->apiServide->callApi($this->url,"PUT",$payload,array($Id_recette));
        return $reponse;
    }

    public function filtrerRecettes(
        ?string $categorie, 
        string|int $duree, 
        ?string $recherche,
        string|int $favori,
        string|int $specialite): 
        ?array 
    {
        return $this->apiServide->callApi($this->url,"GET",null,array($categorie,$duree,$recherche,$favori,$specialite));
    }

    public function getRecetteAleatoire(){
        return $this->laRecette(0);
    }

}
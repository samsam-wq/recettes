<?php

namespace frontend\controleur;

use frontend\Service\ApiService;

class NoterControleur {
    private static ?NoterControleur $instance = null;
    private readonly ApiService $apiServide;
    private string $url = "http://backend.test/Note";

    private function __construct() {
        $this->apiServide = ApiService::getInstance();
    }

    public static function getInstance(): NoterControleur {
        if (self::$instance == null) {
            self::$instance = new NoterControleur();
        }
        return self::$instance;
    }

    public function ajouterNote(
        int $Id_Recette,
        int $note,
        bool $specialite,
        bool $favori
    ):array{
        $payload = [
            'Id_Recette' => $Id_Recette,
            'note' => $note,
            'specialite' => $specialite,
            'favori' => $favori
        ];
        $payload=$this->transfoBool($payload);
        return $this->apiServide->callApi($this->url,"POST",$payload);
    }

    public function supprimerNote(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array($Id_Recette,1));
    }

    public function supprimerNotesRecette(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"DELETE",null,array($Id_Recette));
    }

    public function toutesLesNotes():array{
        return $this->apiServide->callApi($this->url,"GET");
    }

    public function laNote(int $Id_Recette):array{
        return $this->apiServide->callApi($this->url,"GET",null,array($Id_Recette,1));
    }

    public function modifierNote(  
        int $Id_Recette,
        int $note,
        bool $specialite,
        bool $favori
    ):array{
        $payload = [
            'note' => $note,
            'specialite' => $specialite,
            'favori' => $favori
        ];
        $payload=$this->transfoBool($payload);
        return $this->apiServide->callApi($this->url,"PUT",$payload,array($Id_Recette));
    }

    public function lesNotesDuPlat(int $Id_Recette) : array {
        return $this->apiServide->callApi($this->url,"GET",null,array($Id_Recette));
    }

    public function mettreOuEnleverEnfavori(int $Id_Recette) : array {
        $note = $this->laNote($Id_Recette);
        if ($note['status_code']===200){
            $note = $note['data'];
        }else{
            return $this->ajouterNote($Id_Recette,1,false,true);
        }
        $note['favori'] = !$note['favori'];
        return $this->modifierNote($Id_Recette,$note['note'],$note['specialite'],$note['favori']);
    }

    public function mettreOuEnleverSpecialite(int $Id_Recette) : array {
        $note = $this->laNote($Id_Recette);
        if ($note['status_code']===200){
            $note = $note['data'];
        }else{
            return $note;
        }
        $note['specialite'] = !$note['specialite'];
        return $this->modifierNote($Id_Recette,$note['note'],$note['specialite'],$note['favori']);
    }

    public function modifierNoteNote(int $Id_Recette,int $points) : array {
        $note = $this->laNote($Id_Recette);
        if ($note['status_code']===200){
            $note = $note['data'];
        }else{
            return $note;
        }
        $note['note'] = $points;
        return $this->modifierNote($Id_Recette,$note['note'],$note['specialite'],$note['favori']);
    }

    public function transfoBool(array $note):array{
        if ($note['favori']){
            $note['favori']=1;
        }else{
            $note['favori']=0;
        }
        if ($note['specialite']){
            $note['specialite']=1;
        }else{
            $note['specialite']=0;
        }
        return $note;
    }

}
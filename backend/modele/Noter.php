<?php
    class Noter{
        private $Id_Recette;
        private $login;
        private $note;
        private $specialite;
        private $favori;

        public function __construct ($Id_Recette,$login,$note,$specialite,$favori){
            $this->Id_Recette=$Id_Recette;
            $this->login=$login;
            $this->note=$note;
            $this->specialite=$specialite;
            $this->favori=$favori;
        }

        public function getIdRecette(){
            return $this->Id_Recette ;
        }
        public function getLogin(){
            return $this->login ;
        }
        public function getNote(){
            return $this->note ;
        }
        public function getSpecialite(){
            return $this->specialite ;
        }
        public function getFavori(){
            return $this->favori ;
        }

        public function setIdRecette($Id_Recette){
            $this->Id_Recette=$Id_Recette;
        }
        public function setLogin($login){
            $this->login=$login;
        }
        public function setNote($note){
            $this->note=$note;
        }
        public function setSpecialite($specialite){
            $this->specialite=$specialite;
        }
        public function setFavori($favori){
            $this->favori=$favori;
        }
    }
?>
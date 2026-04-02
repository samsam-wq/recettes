<?php
    class Ustensile{
        private $Id_Ustensiles;
        private $nom;

        public function __construct ($Id_Ustensiles,$nom){
            $this->Id_Ustensiles=$Id_Ustensiles;
            $this->nom=$nom;
        }

        public function getIdUstensiles(){
            return $this->Id_Ustensiles ;
        }
        public function getNom(){
            return $this->nom ;
        }

        public function setIdUstensiles($Id_Ustensiles){
            $this->Id_Ustensiles=$Id_Ustensiles;
        }
        public function setNom($nom){
            $this->nom=$nom;
        }
    }
?>
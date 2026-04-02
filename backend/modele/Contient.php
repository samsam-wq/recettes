<?php
    class Contient{
        private $Id_Ingredient;
        private $Id_Etape;
        private $quantite;
        private $unite;

        public function __construct ($Id_Ingredient,$Id_Etape,$quantite,$unite){
            $this->Id_Ingredient=$Id_Ingredient;
            $this->Id_Etape=$Id_Etape;
            $this->quantite=$quantite;
            $this->unite=$unite;
        }

        public function getIdIngredient(){
            return $this->Id_Ingredient ;
        }
        public function getIdEtape(){
            return $this->Id_Etape ;
        }
        public function getQuantite(){
            return $this->quantite ;
        }
        public function getUnite(){
            return $this->unite ;
        }

        public function setIdIngredient($Id_Ingredient){
            $this->Id_Ingredient=$Id_Ingredient;
        }
        public function setIdEtape($Id_Etape){
            $this->Id_Etape=$Id_Etape;
        }
        public function setQuantite($quantite){
            $this->quantite=$quantite;
        }
        public function setUnite($unite){
            $this->unite=$unite;
        }
    }
?>
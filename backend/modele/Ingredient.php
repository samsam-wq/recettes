<?php
    class Ingredient{
        private $Id_Ingredient;
        private $nom;
        private $prix;
        private $image;

        public function __construct ($Id_Ingredient,$nom,$prix,$image){
            $this->Id_Ingredient=$Id_Ingredient;
            $this->nom=$nom;
            $this->prix=$prix;
            $this->image=$image;
        }

        public function getIdIngredient(){
            return $this->Id_Ingredient ;
        }
        public function getNom(){
            return $this->nom ;
        }
        public function getPrix(){
            return $this->prix ;
        }
        public function getImage(){
            return $this->image ;
        }

        public function setIdIngredient($Id_Ingredient){
            $this->Id_Ingredient=$Id_Ingredient;
        }
        public function setNom($nom){
            $this->nom=$nom;
        }
        public function setPrix($prix){
            $this->prix=$prix;
        }
        public function setImage($image){
            $this->image=$image;
        }
    }
?>
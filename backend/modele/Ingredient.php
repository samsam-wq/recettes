<?php
    namespace Backend\Modele;

    class Ingredient{
        private int $Id_Ingredient;
        private string $nom;
        private float $prix;
        private string $image;

        public function __construct (int $Id_Ingredient,string $nom,float $prix,string $image){
            $this->Id_Ingredient=$Id_Ingredient;
            $this->nom=$nom;
            $this->prix=$prix;
            $this->image=$image;
        }

        public function getIdIngredient():int{
            return $this->Id_Ingredient ;
        }
        public function getNom():string{
            return $this->nom ;
        }
        public function getPrix():float{
            return $this->prix ;
        }
        public function getImage():string{
            return $this->image ;
        }

        public function setIdIngredient(int $Id_Ingredient):void{
            $this->Id_Ingredient=$Id_Ingredient;
        }
        public function setNom(string $nom):void{
            $this->nom=$nom;
        }
        public function setPrix(float $prix):void{
            $this->prix=$prix;
        }
        public function setImage(string $image):void{
            $this->image=$image;
        }
    }
?>
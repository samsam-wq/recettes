<?php
    namespace Backend\Modele;

    class Ingredient{
        private int $Id_Ingredient;
        private string $nom;
        private string $image;
        private ?float $quantite;
        private ?string $unite;

        public function __construct (
                int $Id_Ingredient,
                string $nom,
                string $image,
                ?float $quantite = null,
                ?string $unite = null
            ){
            $this->Id_Ingredient=$Id_Ingredient;
            $this->nom=$nom;
            $this->image=$image;
            $this->quantite=$quantite;
            $this->unite=$unite;
        }

        public function toArray(){
            return[
                'Id_Ingredient' => $this->Id_Ingredient ,
                'nom' => $this->nom ,
                'quantite' => $this->quantite,
                'image' => $this->image,
                'unite' => $this->unite
            ];
        }

        public function getIdIngredient():int{
            return $this->Id_Ingredient ;
        }
        public function getNom():string{
            return $this->nom ;
        }
        public function getImage():string{
            return $this->image ;
        }
        public function getQuantite():float{
            return $this->quantite ;
        }
        public function getUnite():string{
            return $this->unite ;
        }

        public function setIdIngredient(int $Id_Ingredient):void{
            $this->Id_Ingredient=$Id_Ingredient;
        }
        public function setNom(string $nom):void{
            $this->nom=$nom;
        }
        public function setImage(string $image):void{
            $this->image=$image;
        }
        public function setQuantite(float $quantite):void{
            $this->quantite=$quantite;
        }
        public function setUnite(string $unite):void{
            $this->unite=$unite;
        }
    }
?>
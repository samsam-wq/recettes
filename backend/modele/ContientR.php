<?php
    namespace Backend\Modele;

    class ContientR{
        private int $Id_Ingredient;
        private int $Id_Recette;
        private float $quantite;
        private string $unite;

        public function __construct (
            int $Id_Ingredient,
            int $Id_Recette,
            float $quantite,
            string $unite)
        {
            $this->Id_Ingredient=$Id_Ingredient;
            $this->Id_Recette=$Id_Recette;
            $this->quantite=$quantite;
            $this->unite=$unite;
        }

        public function getIdIngredient():int{
            return $this->Id_Ingredient ;
        }
        public function getQuantite():float{
            return $this->quantite ;
        }
        public function getUnite():string{
            return $this->unite ;
        }
        public function getIdRecette():int{
            return $this->Id_Recette ;
        }

        public function setIdIngredient(int $Id_Ingredient):void{
            $this->Id_Ingredient=$Id_Ingredient;
        }
        public function setQuantite(float $quantite):void{
            $this->quantite=$quantite;
        }
        public function setUnite(string $unite):void{
            $this->unite=$unite;
        }
        public function setIdRecette(int $Id_Recette):void{
            $this->Id_Recette=$Id_Recette;
        }
    }
?>
<?php
    namespace Backend\Modele;

    class Utilise{
        private int $Id_Ustensiles;
        private int $Id_Recette;
        private float $quantite;

        public function __construct (
            int $Id_Ustensiles,
            int $Id_Recette,
            float $quantite)
        {
            $this->Id_Ustensiles=$Id_Ustensiles;
            $this->Id_Recette=$Id_Recette;
            $this->quantite=$quantite;
        }

        public function getIdUstensiles():int{
            return $this->Id_Ustensiles ;
        }
        public function getQuantite():float{
            return $this->quantite ;
        }
        public function getIdRecette():int{
            return $this->Id_Recette ;
        }

        public function setIdUstensiles(int $Id_Ustensiles):void{
            $this->Id_Ustensiles=$Id_Ustensiles;
        }
        public function setQuantite(float $quantite):void{
            $this->quantite=$quantite;
        }
        public function setIdRecette(int $Id_Recette):void{
            $this->Id_Recette=$Id_Recette;
        }
    }
?>
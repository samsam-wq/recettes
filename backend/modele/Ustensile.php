<?php
    namespace Backend\Modele;

    class Ustensile{
        private ?int $Id_Ustensiles;
        private string $nom;
        private ?int $quantite;

        public function __construct (?int $Id_Ustensiles,string $nom,?int $quantite = null){
            $this->Id_Ustensiles=$Id_Ustensiles;
            $this->nom=$nom;
            $this->quantite=$quantite;

        }

        public function toArray(){
            return[
                'Id_Ustensiles' => $this->Id_Ustensiles ,
                'nom' => $this->nom ,
                'quantite' => $this->quantite 
            ];
        }

        public function getIdUstensiles():int{
            return $this->Id_Ustensiles ;
        }
        public function getNom():string{
            return $this->nom ;
        }

        public function setIdUstensiles(int $Id_Ustensiles):void{
            $this->Id_Ustensiles=$Id_Ustensiles;
        }
        public function setNom(string $nom):void{
            $this->nom=$nom;
        }
    }
?>
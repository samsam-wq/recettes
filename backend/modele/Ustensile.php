<?php
    namespace Backend\Modele;

    class Ustensile{
        private int $Id_Ustensiles;
        private string $nom;

        public function __construct (int $Id_Ustensiles,string $nom){
            $this->Id_Ustensiles=$Id_Ustensiles;
            $this->nom=$nom;
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
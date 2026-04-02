<?php
    class Etape{//Id_Etape, titre, contenu, numero, Id_Recette
        private $Id_Etape;
        private $titre;
        private $contenu;
        private $numero;
        private $Id_Recette;

        public function __construct ($Id_Etape,$titre,$contenu,$numero,$Id_Recette){
            $this->Id_Etape=$Id_Etape;
            $this->titre=$titre;
            $this->contenu=$contenu;
            $this->numero=$numero;
            $this->Id_Recette=$Id_Recette;
        }

        public function getIdEtape(){
            return $this->Id_Etape ;
        }
        public function getTitre(){
            return $this->titre ;
        }
        public function getContenu(){
            return $this->contenu ;
        }
        public function getNumero(){
            return $this->numero ;
        }
        public function getIdRecette(){
            return $this->Id_Recette ;
        }

        public function setIdEtape($Id_Etape){
            $this->Id_Etape=$Id_Etape;
        }
        public function setTitre($titre){
            $this->titre=$titre;
        }
        public function setContenu($contenu){
            $this->contenu=$contenu;
        }
        public function setNumero($numero){
            $this->numero=$numero;
        }
        public function setIdRecette($Id_Recette){
            $this->Id_Recette=$Id_Recette;
        }
    }
?>
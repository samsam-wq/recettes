<?php
    class Recette{
        private $Id_recette;
        private $nom;
        private $duree;
        private $categorie;
        private $image;

        public function __construct ($Id_recette,$nom,$duree,$categorie,$image){
            $this->Id_recette=$Id_recette;
            $this->nom=$nom;
            $this->duree=$duree;
            $this->categorie=$categorie;
            $this->image=$image;
        }

        public function getIdRecette(){
            return $this->Id_recette ;
        }
        public function getNom(){
            return $this->nom ;
        }
        public function getDuree(){
            return $this->duree ;
        }
        public function getCategorie(){
            return $this->categorie ;
        }
        public function getImage(){
            return $this->image ;
        }

        public function setIdRecette($Id_recette){
            $this->Id_recette=$Id_recette;
        }
        public function setNom($nom){
            $this->nom=$nom;
        }
        public function setDuree($duree){
            $this->duree=$duree;
        }
        public function setCategorie($categorie){
            $this->categorie=$categorie;
        }
        public function setImage($image){
            $this->image=$image;
        }
    }
?>
<?php
    namespace Backend\Modele;

    class Recette{
        private int $Id_recette;
        private string $nom;
        private int $duree;
        private string $categorie;
        private string $image;
        private int $groupe;

        public function __construct (
            int $Id_recette,
            string $nom,
            int $duree,
            string $categorie,
            string $image,
            int $groupe)
        {
            $this->Id_recette=$Id_recette;
            $this->nom=$nom;
            $this->duree=$duree;
            $this->categorie=$categorie;
            $this->image=$image;
            $this->groupe=$groupe;
        }

        public function getIdRecette():int{
            return $this->Id_recette ;
        }
        public function getNom():string{
            return $this->nom ;
        }
        public function getDuree():int{
            return $this->duree ;
        }
        public function getCategorie():string{
            return $this->categorie ;
        }
        public function getImage():string{
            return $this->image ;
        }
        public function getGroupe():int{
            return $this->groupe ;
        }

        public function setIdRecette(int $Id_recette):void{
            $this->Id_recette=$Id_recette;
        }
        public function setNom(string $nom):void{
            $this->nom=$nom;
        }
        public function setDuree(int $duree):void{
            $this->duree=$duree;
        }
        public function setCategorie(string $categorie):void{
            $this->categorie=$categorie;
        }
        public function setImage(string $image):void{
            $this->image=$image;
        }
        public function setGroupe(int $groupe):void{
            $this->groupe=$groupe;
        }
    }
?>
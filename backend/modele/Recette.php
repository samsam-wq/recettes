<?php
    namespace Backend\Modele;

    use backend\modele\RecetteCategorie;
    use backend\modele\Noter;

    class Recette{
        private int $Id_recette;
        private string $nom;
        private int $duree;
        private RecetteCategorie $categorie;
        private string $image;
        private int $groupe;
        private ?Noter $notes;

        public function __construct (
            int $Id_recette,
            string $nom,
            int $duree,
            RecetteCategorie $categorie,
            string $image,
            int $groupe)
        {
            $this->Id_recette=$Id_recette;
            $this->nom=$nom;
            $this->duree=$duree;
            $this->categorie=$categorie;
            $this->image=$image;
            $this->groupe=$groupe;
            $this->notes=null;
        }

        public function toArray():array{
            $note = null;
            if (isset($this->notes)){
                $note = $this->notes->toArray();
            }
            return [
                'Id_recette' => $this->getIdRecette() ,
                'nom' => $this->getNom() ,
                'duree' => $this->getDuree() ,
                'categorie' => $this->getCategorie()->name ,
                'image' => $this->getImage() ,
                'groupe' => $this->getGroupe() ,
                'notes' => $note
            ];
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
        public function getCategorie():RecetteCategorie{
            return $this->categorie ;
        }
        public function getImage():string{
            return $this->image ;
        }
        public function getGroupe():int{
            return $this->groupe ;
        }
        public function getNotes():?Noter{
            return $this->notes;
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
        public function setCategorie(RecetteCategorie $categorie):void{
            $this->categorie=$categorie;
        }
        public function setImage(string $image):void{
            $this->image=$image;
        }
        public function setGroupe(int $groupe):void{
            $this->groupe=$groupe;
        }
        public function setNotes(Noter $notes):void{
            $this->notes=$notes;
        }
    }
?>
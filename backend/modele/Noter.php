<?php
    namespace Backend\Modele;

    class Noter{
        private int $Id_Recette;
        private string $login;
        private int $note;
        private bool $specialite;
        private bool $favori;

        public function __construct (
            int $Id_Recette,
            string $login,
            int $note,
            bool $specialite,
            bool $favori)
        {
            $this->Id_Recette=$Id_Recette;
            $this->login=$login;
            $this->note=$note;
            $this->specialite=$specialite;
            $this->favori=$favori;
        }

        public function getIdRecette():int{
            return $this->Id_Recette ;
        }
        public function getLogin():string{
            return $this->login ;
        }
        public function getNote():int{
            return $this->note ;
        }
        public function getSpecialite():bool{
            return $this->specialite ;
        }
        public function getFavori():bool{
            return $this->favori ;
        }

        public function setIdRecette(int $Id_Recette):void{
            $this->Id_Recette=$Id_Recette;
        }
        public function setLogin(string $login):void{
            $this->login=$login;
        }
        public function setNote(int $note):void{
            $this->note=$note;
        }
        public function setSpecialite(bool $specialite):void{
            $this->specialite=$specialite;
        }
        public function setFavori(bool $favori):void{
            $this->favori=$favori;
        }
    }
?>
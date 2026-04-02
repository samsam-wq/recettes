<?php
    class Utilisateur{
        private $login;
        private $mdp;

        public function __construct ($login,$mdp){
            $this->login=$login;
            $this->mdp=$mdp;
        }

        public function getLogin (){
            return $this->login ;
        }
        public function getMdp (){
            return $this->mdp ;
        }

        public function setLogin ($login){
            $this->login=$login;
        }
        public function setMdp ($mdp){
            $this->contenu=$mdp;
        }
    }
?>
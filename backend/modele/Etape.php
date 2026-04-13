<?php
    namespace Backend\Modele;

    class Etape{
        private string $titre;
        private string $contenu;
        private int $numero;
        private int $Id_Recette;

        public function __construct (string $titre,string $contenu,int $numero,int $Id_Recette){
            $this->titre=$titre;
            $this->contenu=$contenu;
            $this->numero=$numero;
            $this->Id_Recette=$Id_Recette;
        }

        public function toArray(){
            return [
                'Id_Recette' => $this->Id_Recette ,
                'numero' => $this->numero ,
                'titre' => $this->titre ,
                'contenu' => $this->contenu 
            ];
        }

        public function getTitre():string{
            return $this->titre ;
        }
        public function getContenu():string{
            return $this->contenu ;
        }
        public function getNumero():int{
            return $this->numero ;
        }
        public function getIdRecette():int{
            return $this->Id_Recette ;
        }

        public function setTitre(string $titre):void{
            $this->titre=$titre;
        }
        public function setContenu(string $contenu):void{
            $this->contenu=$contenu;
        }
        public function setNumero(int $numero):void{
            $this->numero=$numero;
        }
        public function setIdRecette(int $Id_Recette):void{
            $this->Id_Recette=$Id_Recette;
        }
    }
?>
<?php
    class DaoUstensile implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Ustensiles;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $ustensiles = array();
            foreach ( $res as $raw){
                $ustensiles[] = $this->creerInstance($raw);
            }
            return $ustensiles;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Ustensiles where Id_Ustensiles = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO Ustensiles (nom) VALUES (:nom);');
            $req->bindParam(':nom',$donnee->getNom());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Ustensiles 
                SET nom=:nom
                where Id_Ustensiles = :id;');
            $req->bindParam(':id',$donnee->getIdUstensiles());
            $req->bindParam(':nom',$donnee->getNom());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Ustensiles where Id_Ustensiles = :id;');
            $req->bindParam(':id',$id);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $Id_Ustensiles = $raw['Id_Ustensiles'];
            $nom = $raw['nom'];
            $ustensile = new Ustensile($Id_Ustensiles,$nom);
            return $ustensile;
        }
    }
?>
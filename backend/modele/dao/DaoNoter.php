<?php
    class DaoNoter implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Noter;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $noters = array();
            foreach ( $res as $raw){
                $noters[] = $this->creerInstance($raw);
            }
            return $noters;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Noter where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':login', $id[1]);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO Noter (Id_Recette, login, note, specialite, favori) VALUES (:Id_Recette, :login, :note, :specialite, :favori);');
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':login',$donnee->getLogin());
            $req->bindParam(':note',$donnee->getNote());
            $req->bindParam(':specialite',$donnee->getSpecialite());
            $req->bindParam(':favori',$donnee->getFavori());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Noter 
                SET note=:note, specialite=:specialite, favori=:favori
                where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':login',$donnee->getLogin());
            $req->bindParam(':note',$donnee->getNote());
            $req->bindParam(':specialite',$donnee->getSpecialite());
            $req->bindParam(':favori',$donnee->getFavori());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Noter where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':login', $id[1]);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $Id_Recette = $raw['Id_Recette'];
            $login = $raw['login'];
            $note = $raw['note'];
            $specialite = $raw['specialite'];
            $favori = $raw['favori'];
            $noter = new Noter($Id_Recette,$login,$note,$specialite,$favori);
            return $noter;
        }
    }
?>
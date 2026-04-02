<?php
    class DaoUtilisateur implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Utilisateur;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $Utilisateurs = array();
            foreach ( $res as $raw){
                $Utilisateurs[] = $this->creerInstance($raw);
            }
            return $Utilisateurs;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Utilisateur where login = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO  Utilisateur(login,mdp) VALUES (:login,:mdp);');
            $req->bindParam(':login',$donnee->getLogin());
            $req->bindParam(':mdp',$donnee->getMdp());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Utilisateur 
                SET mdp = :mdp 
                where login = :login;');
            $req->bindParam(':login',$donnee->getLogin());
            $req->bindParam(':mdp',$donnee->getMdp());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Utilisateur where login = :id;');
            $req->bindParam(':id',$id);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $login = $raw['login'];
            $mdp = $raw['mdp'];
            $Utilisateurs = new Utilisateur($login,$mdp);
            return $Utilisateurs;
        }
    }
?>
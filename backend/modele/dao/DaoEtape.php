<?php
    class DaoEtape implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Etape;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $etapes = array();
            foreach ( $res as $raw){
                $etapes[] = $this->creerInstance($raw);
            }
            return $etapes;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Etape where Id_Etape = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO Etape (titre, contenu, numero, Id_Recette) VALUES (:titre, :contenu, :numero, :Id_Recette);');
            $req->bindParam(':titre',$donnee->getTitre());
            $req->bindParam(':contenu',$donnee->getContenu());
            $req->bindParam(':numero',$donnee->getNumero());
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Etape 
                SET titre=:titre, contenu=:contenu, numero=:numero, Id_Recette=:Id_Recette
                where Id_Etape = :id;');
            $req->bindParam(':id',$donnee->get());
            $req->bindParam(':titre',$donnee->getTitre());
            $req->bindParam(':contenu',$donnee->getContenu());
            $req->bindParam(':numero',$donnee->getNumero());
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Etape where Id_Etape = :id;');
            $req->bindParam(':id',$id);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $Id_Etape = $raw['Id_Etape'];
            $titre = $raw['titre'];
            $contenu = $raw['contenu'];
            $numero = $raw['numero'];
            $Id_Recette = $raw['Id_Recette'];
            $etape = new Etape($Id_Etape,$titre,$contenu,$numero,$Id_Recette);
            return $etape;
        }
    }
?>
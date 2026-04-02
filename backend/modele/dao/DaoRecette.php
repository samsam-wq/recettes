<?php
    class DaoRecette implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Recette;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $recettes = array();
            foreach ( $res as $raw){
                $recettes[] = $this->creerInstance($raw);
            }
            return $recettes;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Recette where Id_recette = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO Recette (nom, duree, categorie, image) VALUES (:nom, :duree, :categorie, :image);');
            $req->bindParam(':nom',$donnee->getNom());
            $req->bindParam(':duree',$donnee->getDuree());
            $req->bindParam(':categorie',$donnee->getCategorie());
            $req->bindParam(':image',$donnee->getImage());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Recette 
                SET nom=:nom, duree=:duree, categorie=:categorie, image=:image
                where Id_recette = :id;');
            $req->bindParam(':id',$donnee->getIdRecette());
            $req->bindParam(':nom',$donnee->getNom());
            $req->bindParam(':duree',$donnee->getDuree());
            $req->bindParam(':categorie',$donnee->getCategorie());
            $req->bindParam(':image',$donnee->getImage());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Recette where Id_recette = :id;');
            $req->bindParam(':id',$id);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $Id_recette = $raw['Id_recette'];
            $nom = $raw['nom'];
            $duree = $raw['duree'];
            $categorie = $raw['categorie'];
            $image = $raw['image'];
            $recette = new Recette($Id_recette,$nom,$duree,$categorie,$image);
            return $recette;
        }
    }
?>
<?php
    namespace Backend\Modele\Dao;

    use Backend\Modele\Dao\Bd\ConnexionBD;
    use PDO;
    use Backend\Modele\Recette;

    class DaoRecette implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getInstance()->pdo();
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Recette;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $recettes = array();
            foreach ( $res as $raw){
                $recettes[] = $this->creerInstance($raw);
            }
            return $recettes;
        }

        public function findById($id):?Recette{
            $req = $this->connexion->prepare('SELECT * FROM Recette where Id_Recette = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee):bool{
            $req = $this->connexion->prepare('INSERT INTO Recette (nom, duree, categorie, image, groupe) VALUES (:nom, :duree, :categorie, :image, :groupe);');
            $req->bindParam(':nom',$donnee->getNom());
            $req->bindParam(':duree',$donnee->getDuree());
            $req->bindParam(':categorie',$donnee->getCategorie());
            $req->bindParam(':image',$donnee->getImage());
            $req->bindParam(':groupe',$donnee->getGroupe());
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Recette 
                SET nom=:nom, duree=:duree, categorie=:categorie, image=:image, groupe=:groupe
                where Id_Recette = :id;');
            $req->bindParam(':id',$donnee->getIdRecette());
            $req->bindParam(':nom',$donnee->getNom());
            $req->bindParam(':duree',$donnee->getDuree());
            $req->bindParam(':categorie',$donnee->getCategorie());
            $req->bindParam(':image',$donnee->getImage());
            $req->bindParam(':groupe',$donnee->getGroupe());
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Recette where Id_Recette = :id;');
            $req->bindParam(':id',$id);
            return $req->execute();
        }

        private function creerInstance($raw):?Recette{
            if (!$raw){
                return null;
            }
            $Id_recette = $raw['Id_Recette'];
            $nom = $raw['nom'];
            $duree = $raw['duree'];
            $categorie = $raw['categorie'];
            $image = $raw['image'];
            $groupe = $raw['groupe'];
            $recette = new Recette($Id_recette,$nom,$duree,$categorie,$image,$groupe);
            return $recette;
        }
    }
?>

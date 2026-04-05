<?php
    namespace backend\Modele\Dao;

    use backend\modele\RecetteCategorie;
    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Recette;
    use PDO;

    class DaoRecette implements Dao{
        private static ?DaoRecette $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoRecette {
            if (self::$instance == null) {
                self::$instance = new DaoRecette();
            }
            return self::$instance;
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

        public function findByGroupe(int $groupe):?array{
            $req = $this->connexion->prepare('SELECT * FROM Recette where groupe = :groupe;');
            $req->bindParam(':groupe', $groupe);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $recettes = array();
            foreach ( $res as $raw){
                $recettes[] = $this->creerInstance($raw);
            }
            return $recettes;
        }

        public function insert($donnee): bool {
            $req = $this->connexion->prepare('INSERT INTO Recette (nom, duree, categorie, image, groupe) VALUES (:nom, :duree, :categorie, :image, :groupe);');
            
            $nom = $donnee->getNom();
            $duree = $donnee->getDuree();
            $categorie = $donnee->getCategorie()->name;
            $image = $donnee->getImage();
            $groupe = $donnee->getGroupe();
            
            $req->bindParam(':nom', $nom);
            $req->bindParam(':duree', $duree);
            $req->bindParam(':categorie', $categorie);
            $req->bindParam(':image', $image);
            $req->bindParam(':groupe', $groupe);
            
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Recette 
                SET nom=:nom, duree=:duree, categorie=:categorie, image=:image, groupe=:groupe
                where Id_Recette = :id;');

            $id = $donnee->getIdRecette();
            $nom = $donnee->getNom();
            $duree = $donnee->getDuree();
            $categorie = $donnee->getCategorie()->name;
            $image = $donnee->getImage();
            $groupe = $donnee->getGroupe();
            
            $req->bindParam(':id',$id);
            $req->bindParam(':nom', $nom);
            $req->bindParam(':duree', $duree);
            $req->bindParam(':categorie', $categorie);
            $req->bindParam(':image', $image);
            $req->bindParam(':groupe', $groupe);

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
            $categorie = RecetteCategorie::fromName(strtoupper($raw['categorie']));
            $image = $raw['image'];
            $groupe = $raw['groupe'];
            $recette = new Recette($Id_recette,$nom,$duree,$categorie,$image,$groupe);
            return $recette;
        }
    }
?>

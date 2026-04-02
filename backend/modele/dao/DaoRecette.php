<?php
    namespace Backend\Modele\Dao;

    use api\Modele\Joueur\RecetteCategorie;
    use Backend\Modele\Dao\Bd\ConnexionBD;
    use Backend\Modele\Recette;
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

        public function findByCategorie(RecetteCategorie $categorie):?Recette{
            $req = $this->connexion->prepare('SELECT * FROM Recette where categorie = :categorie;');
            $req->bindParam(':categorie', $categorie->name);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function findByGroupe(int $groupe):?Recette{
            $req = $this->connexion->prepare('SELECT * FROM Recette where groupe = :groupe;');
            $req->bindParam(':groupe', $groupe);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function filterByDuree(int $duree):?Recette{
            $req = $this->connexion->prepare('SELECT * FROM Recette where duree <= :duree;');
            $req->bindParam(':duree', $duree);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function findByNom(string $nom):?Recette{
            $req = $this->connexion->prepare('SELECT * FROM Recette where nom LIKE %:nom%;');
            $req->bindParam(':nom', $nom);
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

<?php
    namespace backend\Modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Ingredient;
    use PDO;

    class DaoIngredient implements Dao{
        private static ?DaoIngredient $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoIngredient {
            if (self::$instance == null) {
                self::$instance = new DaoIngredient();
            }
            return self::$instance;
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Ingredient;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $ingredients = array();
            foreach ( $res as $raw){
                $ingredients[] = $this->creerInstance($raw);
            }
            return $ingredients;
        }

        public function findById($id):?Ingredient{
            $req = $this->connexion->prepare('SELECT * FROM Ingredient where Id_Ingredient = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee):string{
            $prix = $donnee->getPrix();
            $image = $donnee->getImage();
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('INSERT INTO Ingredient (prix, image, nom) VALUES (:prix, :image, :nom);');
            $req->bindParam(':prix',$prix);
            $req->bindParam(':image',$image);
            $req->bindParam(':nom',$nom);
            return $req->execute();
        }

        public function update($donnee):bool{
            $id = $donnee->getIdIngredient();
            $prix = $donnee->getPrix();
            $image = $donnee->getImage();
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('UPDATE Ingredient 
                SET prix=:prix, image=:image, nom=:nom
                where Id_Ingredient = :id;');
            $req->bindParam(':id',$id);
            $req->bindParam(':prix',$prix);
            $req->bindParam(':image',$image);
            $req->bindParam(':nom',$nom);
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Ingredient where Id_Ingredient = :id;');
            $req->bindParam(':id',$id);
            return $req->execute();
        }

        private function creerInstance($raw):?Ingredient{
            if (!$raw){
                return null;
            }
            $Id_Ingredient = $raw['Id_Ingredient'];
            $nom = $raw['nom'];
            $prix = $raw['prix'];
            $image = $raw['image'];
            $ingredient = new Ingredient($Id_Ingredient,$nom,$prix,$image);
            return $ingredient;
        }
    }
?>

<?php
    namespace Backend\Modele\Dao;

    use Backend\Modele\Dao\Bd\ConnexionBD;
    use PDO;
    use Backend\Modele\Ingredient;

    class DaoIngredient implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getInstance()->pdo();
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

        public function insert($donnee):bool{
            $req = $this->connexion->prepare('INSERT INTO Ingredient (prix, image, nom) VALUES (:prix, :image, :nom);');
            $req->bindParam(':prix',$donnee->getPrix());
            $req->bindParam(':image',$donnee->getImage());
            $req->bindParam(':nom',$donnee->getNom());
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Ingredient 
                SET prix=:prix, image=:image, nom=:nom
                where Id_Ingredient = :id;');
            $req->bindParam(':id',$donnee->getIdIngredient());
            $req->bindParam(':prix',$donnee->getPrix());
            $req->bindParam(':image',$donnee->getImage());
            $req->bindParam(':nom',$donnee->getNom());
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

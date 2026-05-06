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

        public function findByIdEtape($id):array{
            $req = $this->connexion->prepare('SELECT nom,image,quantite,Ingredient.Id_Ingredient,unite FROM Ingredient 
                JOIN contient ON contient.Id_Ingredient = Ingredient.Id_Ingredient
                where contient.Id_Recette = :id
                and contient.numero= :numero;');
            $req->bindParam(':id', $id[0]);
            $req->bindParam(':numero', $id[1]);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $ustensiles = array();
            foreach ( $res as $raw){
                $ustensiles[] = $this->creerInstance($raw);
            }
            return $ustensiles;
        }

        public function findByIdRecette($id):array{
            $req = $this->connexion->prepare('SELECT Ingredient.nom,Ingredient.Id_Ingredient,Ingredient.image,contient.unite,SUM(contient.quantite)AS quantite FROM Ingredient 
                JOIN contient ON contient.Id_Ingredient = Ingredient.Id_Ingredient
                where contient.Id_Recette = :id
                group by Ingredient.nom,Ingredient.Id_Ingredient,Ingredient.image,contient.unite');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $ustensiles = array();
            foreach ( $res as $raw){
                $ustensiles[] = $this->creerInstance($raw);
            }
            return $ustensiles;
        }

        public function insert($donnee):string{
            $image = $donnee->getImage();
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('INSERT INTO Ingredient ( image, nom) VALUES ( :image, :nom);');
            $req->bindParam(':image',$image);
            $req->bindParam(':nom',$nom);
            $req->execute();
            return $this->connexion->lastInsertId();
        }

        public function update($donnee):bool{
            $id = $donnee->getIdIngredient();
            $image = $donnee->getImage();
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('UPDATE Ingredient 
                SET image=:image, nom=:nom
                where Id_Ingredient = :id;');
            $req->bindParam(':id',$id);
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
            $image = $raw['image'];
            $unite = null;
            if (isset($raw['unite'])){
                $unite = $raw['unite'];
            }
            $quantite = null;
            if (isset($raw['quantite'])){
                $quantite = $raw['quantite'];
            }
            $ingredient = new Ingredient($Id_Ingredient,$nom,$image,$quantite,$unite);
            return $ingredient;
        }
    }
?>

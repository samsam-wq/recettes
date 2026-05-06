<?php
    namespace backend\Modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Contient;
    use PDO;

    class DaoContient{
        private static ?DaoContient $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoContient {
            if (self::$instance == null) {
                self::$instance = new DaoContient();
            }
            return self::$instance;
        }

        public function insert($donnee): bool {
            $idIngredient = $donnee->getIdIngredient();
            $idRecette    = $donnee->getIdRecette();
            $numero       = $donnee->getNumero();
            $quantite     = $donnee->getQuantite();
            $unite        = $donnee->getUnite();
            $req = $this->connexion->prepare('INSERT INTO Contient (Id_Ingredient, Id_Recette, numero, quantite, unite) VALUES (:Id_Ingredient, :Id_Recette, :numero, :quantite, :unite);');
            $req->bindParam(':Id_Ingredient', $idIngredient);
            $req->bindParam(':Id_Recette',    $idRecette);
            $req->bindParam(':numero',        $numero);
            $req->bindParam(':quantite',      $quantite);
            $req->bindParam(':unite',         $unite);
            return $req->execute();
        }

        public function update($donnee): bool {
            $idIngredient = $donnee->getIdIngredient();
            $idRecette    = $donnee->getIdRecette();
            $numero       = $donnee->getNumero();
            $quantite     = $donnee->getQuantite();
            $unite        = $donnee->getUnite();
            $req = $this->connexion->prepare('UPDATE Contient 
                SET quantite=:quantite, unite=:unite
                WHERE Id_Ingredient = :Id_Ingredient AND Id_Recette = :Id_Recette AND numero = :numero;');
            $req->bindParam(':Id_Ingredient', $idIngredient);
            $req->bindParam(':Id_Recette',    $idRecette);
            $req->bindParam(':numero',        $numero);
            $req->bindParam(':quantite',      $quantite);
            $req->bindParam(':unite',         $unite);
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Contient where Id_Ingredient = :Id_Ingredient and Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Ingredient', $id[0]);
            $req->bindParam(':Id_Recette', $id[1]);
            $req->bindParam(':numero', $id[2]);
            return $req->execute();
        }

        public function deleteDeEtape($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Contient where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':numero', $id[1]);
            return $req->execute();
        }

        public function deleteDeRecette($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Contient where Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Recette', $id);
            return $req->execute();
        }
    }
?>

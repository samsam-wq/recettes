<?php
    namespace backend\Modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Utilise;
    use PDO;

    class DaoUtilise{
        private static ?DaoUtilise $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoUtilise {
            if (self::$instance == null) {
                self::$instance = new DaoUtilise();
            }
            return self::$instance;
        }

        public function insert($donnee): bool { 
            $idUstensiles = $donnee->getIdUstensiles();
            $idRecette = $donnee->getIdRecette();
            $numero = $donnee->getNumero();
            $quantite = $donnee->getQuantite();

            $req = $this->connexion->prepare(
                'INSERT INTO Utilise (Id_Ustensiles, Id_Recette, numero, quantite) 
                VALUES (:Id_Ustensiles, :Id_Recette, :numero, :quantite);'
            );

            $req->bindParam(':Id_Ustensiles', $idUstensiles);
            $req->bindParam(':Id_Recette', $idRecette);
            $req->bindParam(':numero', $numero);
            $req->bindParam(':quantite', $quantite);

            return $req->execute(); 
        }

        public function update($donnee): bool {
            $idUstensiles = $donnee->getIdUstensiles();
            $idRecette = $donnee->getIdRecette();
            $numero = $donnee->getNumero();
            $quantite = $donnee->getQuantite();

            $req = $this->connexion->prepare(
                'UPDATE Utilise 
                SET quantite = :quantite
                WHERE Id_Ustensiles = :Id_Ustensiles 
                AND Id_Recette = :Id_Recette 
                AND numero = :numero;'
            );

            $req->bindParam(':Id_Ustensiles', $idUstensiles);
            $req->bindParam(':Id_Recette', $idRecette);
            $req->bindParam(':numero', $numero);
            $req->bindParam(':quantite', $quantite);

            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Utilise where Id_Ustensiles = :Id_Ustensiles and Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Ustensiles', $id[0]);
            $req->bindParam(':Id_Recette', $id[1]);
            $req->bindParam(':numero', $id[2]);
            return $req->execute();
        }

        public function deleteDeEtape($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Utilise where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':numero', $id[1]);
            return $req->execute();
        }

        public function deleteDeRecette($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Utilise where Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Recette', $id);
            return $req->execute();
        }
    }
?>

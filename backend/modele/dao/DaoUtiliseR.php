<?php
    namespace Backend\Modele\Dao;

    use Backend\Modele\Dao\Bd\ConnexionBD;
    use PDO;
    use Backend\Modele\UtiliseR;

    class DaoUtiliseR implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getInstance()->pdo();
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Utilise_R;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $utilises = array();
            foreach ( $res as $raw){
                $utilises[] = $this->creerInstance($raw);
            }
            return $utilises;
        }

        public function findById($id):?UtiliseR{
            $req = $this->connexion->prepare('
                SELECT * FROM Utilise_R 
                    where Id_Ustensiles = :Id_Ustensiles 
                    and Id_Recette = :Id_Recette;
            ');
            $req->bindParam(':Id_Ustensiles', $id[0]);
            $req->bindParam(':Id_Recette', $id[1]);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function findByIdRecette($id):array{
            $req = $this->connexion->prepare('
                SELECT * FROM Utilise_R 
                    where Id_Recette = :Id_Recette;
            ');
            $req->bindParam(':Id_Recette', $id);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $utilises = array();
            foreach ($res as $raw){
                $utilises[] = $this->creerInstance($raw);
            }
            return $utilises;
        }

        public function insert($donnee):bool{
            $req = $this->connexion->prepare('INSERT INTO Utilise_R (Id_Ustensiles, Id_Recette, quantite) VALUES (:Id_Ustensiles, :Id_Recette, :quantite);');
            $req->bindParam(':Id_Ustensiles',$donnee->getIdUstensiles());
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':quantite',$donnee->getQuantite());
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Utilise_R 
                SET quantite=:quantite
                where Id_Ustensiles = :Id_Ustensiles and Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Ustensiles',$donnee->getIdUstensiles());
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':quantite',$donnee->getQuantite());
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Utilise_R where Id_Ustensiles = :Id_Ustensiles and Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Ustensiles', $id[0]);
            $req->bindParam(':Id_Recette', $id[1]);
            return $req->execute();
        }

        private function creerInstance($raw):?UtiliseR{
            if (!$raw){
                return null;
            }
            $Id_Ustensiles = $raw['Id_Ustensiles'];
            $Id_Recette = $raw['Id_Recette'];
            $quantite = $raw['quantite'];
            $utilise = new UtiliseR($Id_Ustensiles,$Id_Recette,$quantite);
            return $utilise;
        }
    }
?>

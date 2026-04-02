<?php
    namespace Backend\Modele\Dao;

    use Backend\Modele\Dao\Bd\ConnexionBD;
    use PDO;
    use Backend\Modele\Ustensile;

    class DaoUstensile implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getInstance()->pdo();
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Ustensiles;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $ustensiles = array();
            foreach ( $res as $raw){
                $ustensiles[] = $this->creerInstance($raw);
            }
            return $ustensiles;
        }

        public function findById($id):?Ustensile{
            $req = $this->connexion->prepare('SELECT * FROM Ustensiles where Id_Ustensiles = :id;');
            $req->bindParam(':id', $id);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee):bool{
            $req = $this->connexion->prepare('INSERT INTO Ustensiles (nom) VALUES (:nom);');
            $req->bindParam(':nom',$donnee->getNom());
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Ustensiles 
                SET nom=:nom
                where Id_Ustensiles = :id;');
            $req->bindParam(':id',$donnee->getIdUstensiles());
            $req->bindParam(':nom',$donnee->getNom());
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Ustensiles where Id_Ustensiles = :id;');
            $req->bindParam(':id',$id);
            return $req->execute();
        }

        private function creerInstance($raw):?Ustensile{
            if (!$raw){
                return null;
            }
            $Id_Ustensiles = $raw['Id_Ustensiles'];
            $nom = $raw['nom'];
            $ustensile = new Ustensile($Id_Ustensiles,$nom);
            return $ustensile;
        }
    }
?>

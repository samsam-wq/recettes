<?php
    namespace backend\modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use PDO;
    use backend\modele\Noter;

    class DaoNoter implements Dao{
        private static ?DaoNoter $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoNoter {
            if (self::$instance == null) {
                self::$instance = new DaoNoter();
            }
            return self::$instance;
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Noter;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $noters = array();
            foreach ( $res as $raw){
                $noters[] = $this->creerInstance($raw);
            }
            return $noters;
        }

        public function findById($id):?object{
            $req = $this->connexion->prepare('SELECT * FROM Noter where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':login', $id[1]);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function findByIdRecette($id):array{
            $req = $this->connexion->prepare('SELECT * FROM Noter where Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Recette', $id);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $noters = array();
            foreach ($res as $raw){
                $noters[] = $this->creerInstance($raw);
            }
            return $noters;
        }

        public function insert($donnee):string{
            $id=$donnee->getIdRecette();
            $login=$donnee->getLogin();
            $note=$donnee->getNote();
            $specialite = $donnee->getSpecialite() ? 1 : 0;
            $favori     = $donnee->getFavori()     ? 1 : 0;
            $req = $this->connexion->prepare('INSERT INTO Noter (Id_Recette, login, note, specialite, favori) VALUES (:Id_Recette, :login, :note, :specialite, :favori);');
            $req->bindParam(':Id_Recette',$id);
            $req->bindParam(':login',$login);
            $req->bindParam(':note',$note);
            $req->bindParam(':specialite',$specialite);
            $req->bindParam(':favori',$favori);
            $req->execute();
            return $this->connexion->lastInsertId();
        }

        public function update($donnee):bool{
            $id=$donnee->getIdRecette();
            $login=$donnee->getLogin();
            $note=$donnee->getNote();
            $specialite = $donnee->getSpecialite() ? 1 : 0;
            $favori     = $donnee->getFavori()     ? 1 : 0;
            $req = $this->connexion->prepare('UPDATE Noter 
                SET note=:note, specialite=:specialite, favori=:favori
                where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette',$id);
            $req->bindParam(':login',$login);
            $req->bindParam(':note',$note);
            $req->bindParam(':specialite',$specialite);
            $req->bindParam(':favori',$favori);
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Noter where Id_Recette = :Id_Recette and login = :login;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':login', $id[1]);
            return $req->execute();
        }

        private function creerInstance($raw):?Noter{
            if (!$raw){
                return null;
            }
            $Id_Recette = $raw['Id_Recette'];
            $login = $raw['login'];
            $note = $raw['note'];
            $specialite = $raw['specialite'];
            $favori = $raw['favori'];
            $noter = new Noter($Id_Recette,$login,$note,$specialite,$favori);
            return $noter;
        }
    }
?>

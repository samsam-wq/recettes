<?php
    namespace backend\Modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Ustensile;
    use PDO;

    class DaoUstensile implements Dao{
        private static ?DaoUstensile $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoUstensile {
            if (self::$instance == null) {
                self::$instance = new DaoUstensile();
            }
            return self::$instance;
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

        public function findByIdEtape($id):array{
            $req = $this->connexion->prepare('SELECT nom,quantite,Ustensiles.Id_Ustensiles FROM Ustensiles 
                JOIN utilise ON utilise.Id_Ustensiles = Ustensiles.Id_Ustensiles
                where utilise.Id_Recette = :id
                and utilise.numero= :numero ;');
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
            $req = $this->connexion->prepare('SELECT Ustensiles.nom,SUM(utilise.quantite)AS quantite FROM Ustensiles 
                JOIN utilise ON utilise.Id_Ustensiles = Ustensiles.Id_Ustensiles 
                where utilise.Id_Recette = :id
                group by Ustensiles.nom;');
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
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('INSERT INTO Ustensiles (nom) VALUES (:nom);');
            $req->bindParam(':nom',$nom);
            $req->execute();
            return $this->connexion->lastInsertId();
        }

        public function update($donnee):bool{
            $id = $donnee->getIdUstensiles();
            $nom = $donnee->getNom();
            $req = $this->connexion->prepare('UPDATE Ustensiles 
                SET nom=:nom
                where Id_Ustensiles = :id;');
            $req->bindParam(':id',$id);
            $req->bindParam(':nom',$nom);
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Ustensiles where Id_Ustensiles = :id;');
            $req->bindParam(':id',$id);
            return $req->execute();
        }

        private function creerInstance(array $raw):?Ustensile{
            if (!$raw){
                return null;
            }
            $Id_Ustensiles = null;
            if (isset($raw['Id_Ustensiles'])){
                $Id_Ustensiles = $raw['Id_Ustensiles'];
            }
            $nom = $raw['nom'];
            $quantite = null;
            if (isset($raw['quantite'])){
                $quantite = $raw['quantite'];
            }
            $ustensile = new Ustensile($Id_Ustensiles,$nom,$quantite);
            return $ustensile;
        }
    }
?>

<?php
    namespace backend\modele\dao;

    use backend\modele\dao\bd\ConnexionBD;
    use backend\modele\Etape;
    use PDO;

    class DaoEtape implements Dao{
        private static ?DaoEtape $instance = null;
        private readonly PDO $connexion;

        private function __construct() {
            $this->connexion = ConnexionBD::getInstance()->pdo();
        }

        public static function getInstance(): DaoEtape {
            if (self::$instance == null) {
                self::$instance = new DaoEtape();
            }
            return self::$instance;
        }

        public function findAll():array{
            $req = $this->connexion->query('SELECT * FROM Etape;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $etapes = array();
            foreach ( $res as $raw){
                $etapes[] = $this->creerInstance($raw);
            }
            return $etapes;
        }

        public function findById($id):?Etape{
            $req = $this->connexion->prepare('
                SELECT * FROM Etape 
                    where Id_Recette = :Id_Recette 
                    and numero = :numero;
            ');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':numero', $id[1]);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function findByIdRecette($id):array{
            $req = $this->connexion->prepare('
                SELECT * FROM Etape 
                    where Id_Recette = :Id_Recette
                    order by numero asc;
            ');
            $req->bindParam(':Id_Recette', $id);
            $req->execute();
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $etapes = array();
            foreach ($res as $raw){
                $etapes[] = $this->creerInstance($raw);
            }
            return $etapes;
        }

        public function insert($donnee):string{
            $id = $donnee->getIdRecette();
            $numero = $donnee->getNumero();
            $titre = $donnee->getTitre();
            $contenu= $donnee->getContenu();
            $req = $this->connexion->prepare('INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES (:Id_Recette, :numero, :titre, :contenu);');
            $req->bindParam(':Id_Recette',$id);
            $req->bindParam(':numero',$numero);
            $req->bindParam(':titre',$titre);
            $req->bindParam(':contenu',$contenu);
            $req->execute();
            return $id . " - " . $numero;
        }

        public function update($donnee):bool{
            $id = $donnee->getIdRecette();
            $numero = $donnee->getNumero();
            $titre = $donnee->getTitre();
            $contenu= $donnee->getContenu();
            $req = $this->connexion->prepare('UPDATE Etape 
                SET titre=:titre, contenu=:contenu
                where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette',$id);
            $req->bindParam(':numero',$numero);
            $req->bindParam(':titre',$titre);
            $req->bindParam(':contenu',$contenu);
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Etape where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':numero', $id[1]);
            return $req->execute();
        }

        public function deleteEtapesDeLarecette($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Etape where Id_Recette = :Id_Recette;');
            $req->bindParam(':Id_Recette', $id);
            return $req->execute();
        }

        private function creerInstance($raw):?Etape{
            if (!$raw){
                return null;
            }
            $Id_Recette = $raw['Id_Recette'];
            $numero = $raw['numero'];
            $titre = $raw['titre'];
            $contenu = $raw['contenu'];
            $etape = new Etape($titre,$contenu,$numero,$Id_Recette);
            return $etape;
        }
    }
?>

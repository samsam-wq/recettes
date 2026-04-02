<?php
    namespace Backend\Modele\Dao;

    use Backend\Modele\Dao\Bd\ConnexionBD;
    use PDO;
    use Backend\Modele\Etape;

    class DaoEtape implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getInstance()->pdo();
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
                    where Id_Recette = :Id_Recette;
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

        public function insert($donnee):bool{
            $req = $this->connexion->prepare('INSERT INTO Etape (Id_Recette, numero, titre, contenu) VALUES (:Id_Recette, :numero, :titre, :contenu);');
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':numero',$donnee->getNumero());
            $req->bindParam(':titre',$donnee->getTitre());
            $req->bindParam(':contenu',$donnee->getContenu());
            return $req->execute();
        }

        public function update($donnee):bool{
            $req = $this->connexion->prepare('UPDATE Etape 
                SET titre=:titre, contenu=:contenu
                where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette',$donnee->getIdRecette());
            $req->bindParam(':numero',$donnee->getNumero());
            $req->bindParam(':titre',$donnee->getTitre());
            $req->bindParam(':contenu',$donnee->getContenu());
            return $req->execute();
        }

        public function delete($id):bool{
            $req = $this->connexion->prepare('DELETE FROM Etape where Id_Recette = :Id_Recette and numero = :numero;');
            $req->bindParam(':Id_Recette', $id[0]);
            $req->bindParam(':numero', $id[1]);
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

<?php
    class DaoContient implements Dao{
        private $connexion;

        public function __construct (){
            $this->connexion=ConnexionBD::getConnexion();
        }

        public function findAll(){
            $req = $this->connexion->query('SELECT * FROM Contient;');
            $res = $req->fetchAll(PDO::FETCH_ASSOC);
            $contients = array();
            foreach ( $res as $raw){
                $contients[] = $this->creerInstance($raw);
            }
            return $contients;
        }

        public function findById($id){
            $req = $this->connexion->prepare('SELECT * FROM Contient where Id_Ingredient = :Id_Ingredient and Id_Etape = :Id_Etape;');
            $req->bindParam(':Id_Ingredient', $id[0]);
            $req->bindParam(':Id_Etape', $id[1]);
            $req->execute();
            $res = $req->fetch(PDO::FETCH_ASSOC);
            return $this->creerInstance($res);
        }

        public function insert($donnee){
            $req = $this->connexion->prepare('INSERT INTO Contient (Id_Ingredient, Id_Etape, quantite, unite) VALUES (Id_Ingredient, Id_Etape, quantite, unite);');
            $req->bindParam(':Id_Ingredient',$donnee->getIdIngredient());
            $req->bindParam(':Id_Etape',$donnee->getIdEtape());
            $req->bindParam(':quantite',$donnee->getQuantite());
            $req->bindParam(':unite',$donnee->getUnite());
            $req->execute();
        }

        public function update($donnee){
            $req = $this->connexion->prepare('UPDATE Contient 
                SET quantite=:quantite, unite=:unite
                where Id_Ingredient = :Id_Ingredient and Id_Etape = :Id_Etape;');
            $req->bindParam(':Id_Ingredient',$donnee->getIdIngredient());
            $req->bindParam(':Id_Etape',$donnee->getIdEtape());
            $req->bindParam(':quantite',$donnee->getQuantite());
            $req->bindParam(':unite',$donnee->getUnite());
            $req->execute();
        }

        public function delete($id){
            $req = $this->connexion->prepare('DELETE FROM Contient where Id_Ingredient = :Id_Ingredient and Id_Etape = :Id_Etape;');
            $req->bindParam(':Id_Ingredient', $id[0]);
            $req->bindParam(':Id_Etape', $id[1]);
            $req->execute();
        }

        private function creerInstance($raw){
            if (!$raw){
                return null;
            }
            $Id_Ingredient = $raw['Id_Ingredient'];
            $Id_Etape = $raw['Id_Etape'];
            $quantite = $raw['quantite'];
            $unite = $raw['unite'];
            $contient = new Contient($Id_Ingredient,$Id_Etape,$quantite,$unite);
            return $contient;
        }
    }
?>
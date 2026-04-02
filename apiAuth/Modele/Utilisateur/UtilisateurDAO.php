<?php

namespace apiAuth\Modele\Utilisateur;

use apiAuth\Modele\DatabaseHandler;

class UtilisateurDAO {
    private static ?UtilisateurDAO $instance = null;
    private readonly DatabaseHandler $database;

    public function __construct() {
        $this->database = DatabaseHandler::getInstance();
    }

    public static function getInstance(): UtilisateurDAO {
        if (self::$instance == null) {
            self::$instance = new UtilisateurDAO();
        }
        return self::$instance;
    }

    function isUserValide($login,$pwd):bool{
        $req = $this->database->pdo()->prepare('SELECT * FROM utilisateur where login = :login and password = :pwd;');
        $req->bindParam(':login', $login);
        $req->bindParam(':pwd', $pwd);
        $req->execute();
        $res = $req->fetchAll($this->database->pdo()::FETCH_ASSOC);
        if ($res){
            return true;
        }else{
            return false;
        }
    }

    function getUser($login,$pwd):Utilisateur{
        $req = $this->database->pdo()->prepare('SELECT * FROM utilisateur where login = :login and password = :pwd;');
        $req->bindParam(':login', $login);
        $req->bindParam(':pwd', $pwd);
        $req->execute();
        $res = $req->fetch($this->database->pdo()::FETCH_ASSOC);
        return new Utilisateur($res['login'],$res['password'],$res['groupe']);
    }
}
<?php

namespace Backend\Modele\Dao\Bd;

use Exception;
use PDO;

class ConnexionBD {
    private static ?ConnexionBD $instance = null;
    private readonly PDO $linkpdo;
    private readonly string $server;
    private readonly string $db;
    private readonly string $login;
    private readonly string $mdp;

    private function __construct(){
        try{
            $this->server = "127.0.0.1";
            $this->db = "Recettes";
            $this->login = "root";
            $this->mdp = '';
            $this->linkpdo=new PDO("mysql:host=".$this->server.";dbname=".$this->db,$this->login,$this->mdp);
        }catch(Exception $e){
            die("Erreur : ".$e->getMessage());
        }
    }

    public static function getInstance(): ConnexionBD
    {
        if (self::$instance == null) {
            self::$instance = new ConnexionBD();
        }
        return self::$instance;
    }

    public function pdo(): PDO {
        return $this->linkpdo;
    }
}
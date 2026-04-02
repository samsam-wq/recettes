<?php

namespace apiAuth\Modele;

use Exception;
use PDO;

class DatabaseHandler {
    private static ?DatabaseHandler $instance = null;
    private readonly PDO $linkpdo;
    private readonly string $server;
    private readonly string $db;
    private readonly string $login;
    private readonly string $mdp;

    private function __construct(){
        try{
            $this->server = "mysql-lafrontt.alwaysdata.net";
            $this->db = "lafrontt_apiauth";
            $this->login = "lafrontt_requetes_api";
            $this->mdp = "\$iutinfo";
            $this->linkpdo=new PDO("mysql:host=".$this->server.";dbname=".$this->db,$this->login,$this->mdp);
        }catch(Exception $e){
            die("Erreur : ".$e->getMessage());
        }
    }

    public static function getInstance(): DatabaseHandler
    {
        if (self::$instance == null) {
            self::$instance = new DatabaseHandler();
        }
        return self::$instance;
    }

    public function pdo(): PDO {
        return $this->linkpdo;
    }
}
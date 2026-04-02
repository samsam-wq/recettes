<?php
class ConnexionBD {
    private $server = '127.0.0.1';
    private $db = 'perso';
    private $login = 'root';
    private $mdp = '';
    private static $connexion = null;

    private function __construct() {
        try {
            self::$connexion = new PDO(
                "mysql:host={$this->server};dbname={$this->db}",
                $this->login,
                $this->mdp,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    public static function getConnexion() {
        if (self::$connexion === null) {
            new ConnexionBD(); 
        }
        return self::$connexion;
    }
}
?>

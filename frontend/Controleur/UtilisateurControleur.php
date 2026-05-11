<?php

namespace frontend\Controleur;

use frontend\Service\ApiService;

class UtilisateurControleur {
    private static ?UtilisateurControleur $instance = null;
    private string $url = "https://lafront.alwaysdata.net/";
    private ApiService $apiservice;

    private function __construct() {
        $this->apiservice = ApiService::getInstance();
    }

    public static function getInstance(): UtilisateurControleur {
        if (self::$instance == null) {
            self::$instance = new UtilisateurControleur();
        }
        return self::$instance;
    }

    public function seConnecter(string $username, string $password): String {
        $payload = [
            "login" => $username,
            "password" => $password
        ];

        $responseData = $this->apiservice->callApi($this->url,"POST",$payload,array("connexion"));

        if ($responseData['status_code'] != 200) {
            return false;
        } else {
            if ($this->apiservice->isTokenValid($responseData['data'])){
                return $responseData['data'];
            }else{
                return false;
            }
        }
    }

    public function getGroupe(string $jwt): ?string
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) < 2) {
            return null;
        }

        $payload = base64_decode($tokenParts[1]);
        $payloadData = json_decode($payload);

        return $payloadData->role ?? null;
    }

    public static function verifieRole(string $role,array $autorises):bool{
        foreach ($autorises as $autorise){
            if ($role === $autorise){
                return true;
            }
        }
        return false;
    }

    public function getLogin(string $jwt): ?string
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) < 2) {
            return null;
        }

        $payload = base64_decode($tokenParts[1]);
        $payloadData = json_decode($payload);

        return $payloadData->login ?? null;
    }

    public function ajouterUtilisateur(
            string $login,
            string $motDePasse
        ){
        $payload = [
            "login" => $login,
            "password" => $motDePasse
        ];
        return $this->apiservice->callApi($this->url,"POST",$payload,array("inscription"));
    }

    public function modifierMdp(
        string $login,
        string $motDePasse,
        string $nvMdp
    ){
        $payload = [
            "password" => $motDePasse,
            "newPassword" => $nvMdp
        ];
        return $this->apiservice->callApi($this->url,"PATCH",$payload,array($login));
    }

    public function modifierGroupe(
        string $login,
        string $motDePasse,
        string $nvGrp
    ){
        $payload = [
            "password" => $motDePasse,
            "newGroupe" => $nvGrp
        ];
        return $this->apiservice->callApi($this->url,"PATCH",$payload,array($login));
    }

    public function supprimerUtilisateur(
        string $login,
        string $motDePasse
    ):array{
        $payload = [
            "password" => $motDePasse
        ];
        return $this->apiservice->callApi($this->url,"DELETE",$payload,array($login));
    }
}
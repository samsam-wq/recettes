<?php

namespace frontend\Service;

class ApiService
{
    private static ?ApiService $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): ApiService
    {
        if (self::$instance === null) {
            self::$instance = new ApiService();
        }
        return self::$instance;
    }

    public function callApi(
            String $url,
            String $method,
            array|null $payload = null,
            array|null $forUrl = null)
            :array
        {
        $ch = curl_init();
        //rajoute les paramètres à l'url
        if (isset($forUrl)){
            foreach ($forUrl as $parametre) {
                $url = $url . "/" . $parametre;
            }
        }

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //met le token dans le header
        if (isset($_SESSION['token'])) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Authorization: Bearer ".$_SESSION['token'],
                "Content-Type: application/json"
            ]);
        //cas pour le login
        }else{
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                "Content-Type: application/json"
            ]);
        }
        //sélectionne la méthode et rajoute la payload si besoin
        $method = strtoupper(trim($method));
        switch($method){
            case "POST":
                curl_setopt($ch, CURLOPT_POST, true);
                if ($payload){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                }
                break;
            case "DELETE":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
                break;
            case "PUT":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
                if ($payload){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                }
                break;
            case "PATCH":
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PATCH");
                if ($payload !== null){
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
                }
    break;
        }
        $response = curl_exec($ch);
        //décode la réponse
        $responseData = json_decode($response, true);
        return $responseData ?? [];
    }

    public function isTokenValid($token):bool {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "https://lafrontt.alwaysdata.net/");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer ".$token,
            "Content-Type: application/json"
        ]);
        
        $response = curl_exec($ch);

        $responseData = json_decode($response, true);

        return $responseData['status_code']==200;
    }
}
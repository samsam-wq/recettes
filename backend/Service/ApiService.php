<?php

namespace backend\Service;

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

    public function getAuthorizationHeader(): ?string{
        $headers = null;

        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) { //Nginx or fast CGI
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } else if (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            // Server-side fix for bug in old Android versions (a nice side-effect of this fix means we don't care about capitalization for Authorization)
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            //print_r($requestHeaders);
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
        }

        return $headers;
    }

    public function getBearerToken(): ?string {
        $headers = $this->getAuthorizationHeader();
        
        // HEADER: Get the access token from the header
        if (!empty($headers)) {
            if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
                if($matches[1]=='null') //$matches[1] est de type string et peut contenir 'null'
                    return null;
                else
                    return $matches[1];
            }
        }
        return null;
    }

    public function deliverResponse(int $statusCode, string $statusMessage, mixed $data = null): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');

        $response = [
            'status_code' => $statusCode,
            'status_message' => $statusMessage,
            'data' => $data
        ];

        $jsonResponse = json_encode($response);

        if ($jsonResponse === false) {
            die('json encode ERROR : ' . json_last_error_msg());
        }

        echo $jsonResponse;
    }

    public function getGroupe(string $jwt): ?int
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) < 2) {
            return null;
        }

        $payload = base64_decode($tokenParts[1]);
        $payloadData = json_decode($payload);

        return $payloadData->role ?? null;
    }

    public function toArrayList(array $objects):array{
        $array = array();
        foreach ($objects as $object){
            $array[]=$object->toArray();
        }
        return $array;
    }
}
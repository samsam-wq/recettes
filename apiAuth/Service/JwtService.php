<?php

namespace apiAuth\Service;

class JwtService
{
    private static ?JwtService $instance = null;

    private function __construct()
    {
    }

    public static function getInstance(): JwtService
    {
        if (self::$instance === null) {
            self::$instance = new JwtService();
        }
        return self::$instance;
    }

    public function generateJwt(array $headers, array $payload, string $secret): string
    {
        $headersEncoded = $this->base64urlEncode(json_encode($headers));
        $payloadEncoded = $this->base64urlEncode(json_encode($payload));

        $signature = hash_hmac('SHA256', $headersEncoded . '.' . $payloadEncoded, $secret, true);
        $signatureEncoded = $this->base64urlEncode($signature);

        return $headersEncoded . '.' . $payloadEncoded . '.' . $signatureEncoded;
    }

    private function base64urlEncode(string $data): string
    {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
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

    public function isJwtValid(string $jwt, string $secret): bool
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) !== 3) {
            return false;
        }

        $header = base64_decode($tokenParts[0]);
        $payload = base64_decode($tokenParts[1]);
        $signatureProvided = $tokenParts[2];

        $payloadData = json_decode($payload);

        if ($payloadData === null || !isset($payloadData->exp)) {
            return false;
        }

        $expiration = $payloadData->exp;
        $isTokenExpired = ($expiration - time()) < 0;

        $base64UrlHeader = $this->base64urlEncode($header);
        $base64UrlPayload = $this->base64urlEncode($payload);
        $signature = hash_hmac('SHA256', $base64UrlHeader . "." . $base64UrlPayload, $secret, true);
        $base64UrlSignature = $this->base64urlEncode($signature);

        $isSignatureValid = ($base64UrlSignature === $signatureProvided);

        return !$isTokenExpired && $isSignatureValid;
    }

    public function getRole(string $jwt): ?string
    {
        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) < 2) {
            return null;
        }

        $payload = base64_decode($tokenParts[1]);
        $payloadData = json_decode($payload);

        return $payloadData->role ?? null;
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
}
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use backend\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('backend\\', $_SERVER['DOCUMENT_ROOT']);

    use backend\Service\ApiService;

    $apiService = ApiService::getInstance();

    $token = $apiService->getBearerToken();
    if (!$token || !$apiService->isTokenValid($token)){
        $authentified = false;
    }else{
        $authentified = true;
        $groupe = $apiService->getGroupe($token);
        $login = $apiService->getLogin($token);
    }
    
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        case "GET" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }
            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            //byId
            if (isset($id )&& ctype_digit($id)) {

            }

            break;
        case "POST" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData,true);
            if ($data === null) {
                $apiService->deliverResponse(400, "JSON invalide");
                break;
            }

            $champsManquants = [];
            if (empty($data['nom'])) $champsManquants[] = 'nom';
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            break;
        case "DELETE" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }
            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            if ($id && ctype_digit($id)) {

            }else{
                $apiService->deliverResponse(400, "Champs joueurId manquant");
                break;
            }
            break;
        case "PUT" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }
            $postedData = file_get_contents('php://input');
            $data = json_decode($postedData,true);
            if ($data === null) {
                $apiService->deliverResponse(400, "JSON invalide");
                break;
            }
            
            $champsManquants = [];
            if (empty($data['nom'])) $champsManquants[] = 'nom';
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            if ($id && ctype_digit($id)) {
                
            }else{
                $apiService->deliverResponse(400, "Champs Id manquant");
                break;
            }
            break;
        default :
            $apiService->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
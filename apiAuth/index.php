<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use apiAuth\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('apiAuth\\', $_SERVER['DOCUMENT_ROOT']);

    use apiAuth\Controleur\UtilisateurControleur;
    use apiAuth\Service\JwtService;

    $utilisateurControleur = UtilisateurControleur::getInstance();
    $jwt_utils = JwtService::getInstance();

    /// Identification du type de méthode HTTP envoyée par le client
    $http_method = $_SERVER['REQUEST_METHOD'];
    switch ($http_method){
        //verification de la validité du token
        case "GET" :
            $tkn = $jwt_utils->getBearerToken();
            if ($tkn) {
                if ($jwt_utils->isJwtValid($tkn, $_SERVER['JWT_SECRET'])) {
                    $jwt_utils->deliverResponse(200, "[R401 REST AUTH] : Token Valide");
                } else {
                    $jwt_utils->deliverResponse(401, "[R401 REST AUTH] : Unauthorized");
                }
            } else {
                $jwt_utils->deliverResponse(400, "Syntaxe de la requête non conforme");
            }
            break;
        //creation d'un token
        case "POST" :
            try{
                $postedData = file_get_contents('php://input');
                $data = json_decode($postedData,true);
                if ($data['login']==null || $data['password']==null 
                    || $data['login']=="" || $data['password']=="" 
                    || !isset($data['login']) || !isset($data['password'])){
                    $jwt_utils->deliverResponse(400, "Login et/ou mot de passe absent");
                    break;
                }
                $user = $utilisateurControleur->seConnecter($data['login'],$data['password']);
                if ($user){
                    //creation du token
                    $header = ['alg' => 'HS256','typ' => 'JWT'];
                    $payload = ['login' => $user->getLogin(),'role' => $user->getGroupe(),'exp' => time()+7200];
                    $token = $jwt_utils->generateJwt($header,$payload,$_SERVER['JWT_SECRET']);
                    $jwt_utils->deliverResponse(200, "[R401 REST AUTH] : Authentification OK",$token);
                }else{
                    $jwt_utils->deliverResponse(401, "Login et/ou mot de passe erroné");
                }
            }catch (Exception $e) {
                return $jwt_utils->deliverResponse(500, "Erreur serveur");
            }
            break;
        default :
            $jwt_utils->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
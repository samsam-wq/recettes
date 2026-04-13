<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use backend\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('backend\\', $_SERVER['DOCUMENT_ROOT']);

    use backend\Service\ApiService;
    use backend\controleur\EtapeControleur;

    $apiService = ApiService::getInstance();
    $etapeControleur = EtapeControleur::getInstance();

    $token = $apiService->getBearerToken();
    if (!$token || !$apiService->isTokenValid($token)){
        $authentified = false;
    }else{
        $authentified = true;
        $groupe = $apiService->getGroupe($token);
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
            $numero = $segments[3] ?? null;
            //byId
            if (isset($id )&& ctype_digit($id) && isset($numero )&& ctype_digit($numero)) {
                $etape = $etapeControleur->letape($id,$numero);
                if ($etape){
                    $etape = $etape->toArray();
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$etape);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            }elseif(isset($id )&& ctype_digit($id)){
                $etapes = $etapeControleur->lesEtapesDuPlat($id);
                if ($etapes){
                    $etapes = $apiService->toArrayList($etapes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$etapes);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }   
            }else{
                $etapes = $etapeControleur->toutesLesEtapes();
                if ($etapes){
                    $etapes = $apiService->toArrayList($etapes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$etapes);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
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
            if (empty($data['Id_Recette'])) $champsManquants[] = 'Id_Recette';
            if (empty($data['numero'])) $champsManquants[] = 'numero';
            if (empty($data['titre'])) $champsManquants[] = 'titre';
            if (empty($data['contenu'])) $champsManquants[] = 'contenu';
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            try{
                $id = $etapeControleur->ajouterEtape($data['titre'],$data['contenu'],$data['numero'],$data['Id_Recette']);
                if ($id) {
                    $apiService->deliverResponse(201, "Donnees insérée avec succes.",$id);
                }else{
                    $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                }
            }catch (Exception $e){
                $apiService->deliverResponse(400, $e->getMessage());
            }
            break;
        case "DELETE" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }
            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            $numero = $segments[3] ?? null;
            if (isset($id )&& ctype_digit($id) && isset($numero )&& ctype_digit($numero)) {
                try {
                    $statut = $etapeControleur->supprimerEtape($id,$numero);
                    if ($statut) {
                        $apiService->deliverResponse(201, "Donnees supprimée avec succes.");
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }elseif(isset($id )&& ctype_digit($id)){
                try {
                    $statut = $etapeControleur->supprimeretapesRecette($id);
                    if ($statut) {
                        $apiService->deliverResponse(201, "Donnees supprimée avec succes.");
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }  
            }else{
                $apiService->deliverResponse(400, "Champs Id manquant");
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
            if (empty($data['titre'])) $champsManquants[] = 'titre';
            if (empty($data['contenu'])) $champsManquants[] = 'contenu';
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            $numero = $segments[3] ?? null;
            if (isset($id )&& ctype_digit($id) && isset($numero )&& ctype_digit($numero)) {
                try{
                    $id = $etapeControleur->modifierEtape($data['titre'],$data['contenu'],$numero,$id);
                    if ($id) {
                        $apiService->deliverResponse(200, "Donnees modifiees avec succes.",$id);
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }                
            }else{
                $apiService->deliverResponse(400, "Champs Id manquant");
                break;
            }
            break;
        default :
            $apiService->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
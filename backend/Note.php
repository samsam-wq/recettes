<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use backend\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('backend\\', $_SERVER['DOCUMENT_ROOT']);

    use backend\Service\ApiService;
    use backend\controleur\NoterControleur;

    $apiService = ApiService::getInstance();
    $noterControleur = NoterControleur::getInstance();

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
            $loginUrl = $segments[3] ?? null;
            if (isset($id )&& ctype_digit($id) && isset($loginUrl)) {
                $note = $noterControleur->laNote($id,$login);
                if ($note){
                    $note = $note->toArray();
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$note);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            }elseif(isset($id )&& ctype_digit($id)){
                $notes = $noterControleur->lesNotesDuPlat($id);
                if ($notes){
                    $notes = $apiService->toArrayList($notes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$notes);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            }else{
                $notes = $noterControleur->toutesLesNotes();
                if ($notes){
                    $notes = $apiService->toArrayList($notes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$notes);
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
            if (empty($data['note'])) $champsManquants[] = 'note';
            if ($data['favori']===0) {
                $favori=false;
            }elseif($data['favori']===1){
                $favori=true;
            }else{
                $champsManquants[] = 'favori';
            }
            if ($data['specialite']===0) {
                $specialite=false;
            }elseif($data['specialite']===1){
                $specialite=true;
            }else{
                $champsManquants[] = 'specialite';
            }
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            try{
                $id = $noterControleur->ajouterNote($data['Id_Recette'],$login,$data['note'],$specialite,$favori);
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
            $loginUrl = $segments[2] ?? null;
            if ($id && ctype_digit($id) && $loginUrl) {
                try {
                    $statut = $noterControleur->supprimerNote($id,$login);
                    if ($statut) {
                        $apiService->deliverResponse(201, "Donnees supprimée avec succes.");
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }elseif($id && ctype_digit($id)){
                try {
                    $statut = $noterControleur->supprimerNotesRecette($id);
                    if ($statut) {
                        $apiService->deliverResponse(201, "Donnees supprimée avec succes.");
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }else{
                $apiService->deliverResponse(400, "Champs id manquant");
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

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            if (!$id || !ctype_digit($id)) {
                $apiService->deliverResponse(400, "Champs Id manquant");
                break;
            }

            $champsManquants = [];
            if (empty($data['note'])) $champsManquants[] = 'note';
            if ($data['favori']===0) {
                $favori=false;
            }elseif($data['favori']===1){
                $favori=true;
            }else{
                $champsManquants[] = 'favori';
            }
            if ($data['specialite']===0) {
                $specialite=false;
            }elseif($data['specialite']===1){
                $specialite=true;
            }else{
                $champsManquants[] = 'specialite';
            }
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            try{
                $id = $noterControleur->modifierNote($id,$login,$data['note'],$specialite,$favori);
                if ($id) {
                    $apiService->deliverResponse(200, "Donnees modifiees avec succes.",$id);
                }else{
                    $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                }
            }catch (Exception $e){
                $apiService->deliverResponse(400, $e->getMessage());
            }
            break;
        default :
            $apiService->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
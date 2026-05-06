<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use backend\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('backend\\', $_SERVER['DOCUMENT_ROOT']);

    use backend\Service\ApiService;
    use backend\controleur\IngredientControleur;
    use backend\controleur\ContientControleur;

    $apiService = ApiService::getInstance();
    $ingredientControleur = IngredientControleur::getInstance();
    $contientControleur = ContientControleur::getInstance();

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
            $type = $segments[2] ?? null;
            $id = $segments[3] ?? null;
            $numero = $segments[4] ?? null;
            if (isset($type )&& ctype_digit($type)) {
                $ustensile = $ingredientControleur->lIngredient($type);
                if ($ustensile){
                    $ustensile = $ustensile->toArray();
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.(id)",$ustensile);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            }elseif(isset($type) && isset($id)){
                if (trim($type) === "recette"){
                    $ustensiles = $ingredientControleur->tousLesIngredientDeRecette($id);
                    if ($ustensiles){
                        $ustensiles = $apiService->toArrayList($ustensiles);
                        $apiService->deliverResponse(200, "Donnée récupérée avec succès (recette).",$ustensiles);
                    }else{
                        $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                    }
                }elseif(trim($type) === "etape" && isset($numero)){
                    $ustensiles = $ingredientControleur->tousLesIngredientDeEtape($id,$numero);
                    if ($ustensiles){
                        $ustensiles = $apiService->toArrayList($ustensiles);
                        $apiService->deliverResponse(200, "Donnée récupérée avec succès(etape).",$ustensiles);
                    }else{
                        $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                    }
                }else {
                    $apiService->deliverResponse(400, "Champs Id manquant");
                    break;
                }
            }else{
                $ustensiles = $ingredientControleur->tousLesIngredient();
                if ($ustensiles){
                    $ustensiles = $apiService->toArrayList($ustensiles);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$ustensiles);
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

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $type = $segments[2] ?? null;
            if (empty($type)) {
                if (empty($data['nom'])) {
                    $apiService->deliverResponse(400, "Champs nom manquant");
                    break;
                }
                try {
                    $id = $ingredientControleur->ajouterIngredient($data['nom']);
                    if ($id) {
                        $apiService->deliverResponse(201, "Donnees insérée avec succes.",$id);
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }elseif(!empty($type) && $type==="etape"){
                $champsManquants = [];
                if (empty($data['Id_Ingredient'])) $champsManquants[] = 'Id_Ingredient';
                if (empty($data['Id_Recette'])) $champsManquants[] = 'Id_Recette';
                if (empty($data['numero'])) $champsManquants[] = 'numero';
                if (empty($data['quantite'])) $champsManquants[] = 'quantite';
                if (empty($data['unite'])) $champsManquants[] = 'unite';
                if (!empty($champsManquants)) {
                    $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                    $apiService->deliverResponse(400, $message);
                    break;
                }
                try {
                    $id = $contientControleur->ajouterContient(
                        $data['Id_Ingredient'],
                        $data['Id_Recette'],
                        $data['numero'],
                        $data['quantite'],
                        $data['unite']);
                    if ($id) {
                        $apiService->deliverResponse(201, "Donnees insérée avec succes.",$id);
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }else{
                $apiService->deliverResponse(400, "Champs type manquant ou mauvaise URL");
            }       
            break;
        case "DELETE" :
            if (!$authentified){
                $apiService->deliverResponse(401, "Jeton JWT inconnu : Une authentification est nécessaire pour accéder à la ressource.");
                break;
            }

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $type = $segments[2] ?? null;
            $Id_Recette = $segments[3] ?? null;
            $numero = $segments[4] ?? null;
            $Id_Ingredient = $segments[5] ?? null;
            if (empty($type) && !empty($Id_Ingredient)) {
                try {
                    $statut = $ingredientControleur->supprimerIngredient($Id_Ingredient);
                    if ($statut) {
                        $apiService->deliverResponse(200, "Donnees supprimées avec succes.",$statut);
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }elseif(!empty($type) && $type==="etape" && !empty($Id_Recette) && !empty(($numero))){
                if (!empty($Id_Ingredient)) {
                    try {
                        $statut = $contientControleur->supprimerContient($Id_Ingredient,$Id_Recette,$numero);
                        if ($statut) {
                            $apiService->deliverResponse(200, "Donnees supprimées avec succes.",$statut);
                        }else{
                            $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                        }
                    }catch (Exception $e){
                        $apiService->deliverResponse(400, $e->getMessage());
                    }
                }else{
                    try {
                        $statut = $contientControleur->supprimerContientEtape($Id_Recette,$numero);
                        if ($statut) {
                            $apiService->deliverResponse(200, "Donnees supprimées avec succes.",$statut);
                        }else{
                            $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                        }
                    }catch (Exception $e){
                        $apiService->deliverResponse(400, $e->getMessage());
                    }
                }
            }elseif(!empty($type) && $type==="recette" && !empty($Id_Recette)){
                try {
                    $statut = $contientControleur->supprimerContientRecette($Id_Recette);
                    if ($statut) {
                        $apiService->deliverResponse(200, "Donnees supprimées avec succes.",$statut);
                    }else{
                        $apiService->deliverResponse(400, "Données non supprimées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }else{
                $apiService->deliverResponse(400, "Champs type manquant ou mauvaise URL");
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
            $type = $segments[2] ?? null;
            $Id_Recette = $segments[3] ?? null;
            $numero = $segments[4] ?? null;
            $Id_Ingredient = $segments[5] ?? null;
            if (empty($type) && !empty($Id_Ingredient)) {
                if (empty($data['nom'])) {
                    $apiService->deliverResponse(400, "Champs nom manquant");
                    break;
                }
                try {
                    $id = $ingredientControleur->modifierIngredient($Id_Ingredient,$data['nom']);
                    if ($id) {
                        $apiService->deliverResponse(200, "Donnees modifiée avec succes.",$id);
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }elseif(!empty($type) && $type==="etape" && !empty($Id_Recette) && !empty($numero) && !empty($Id_Ingredient)){
                if (empty($data['quantite'])) {
                    $apiService->deliverResponse(400, "Champs nom manquant");
                    break;
                }
                if (empty($data['unite'])) {
                    $apiService->deliverResponse(400, "Champs nom manquant");
                    break;
                }
                try {
                    $id = $contientControleur->modifierContient(
                        $Id_Ingredient,
                        $Id_Recette,
                        $numero,
                        $data['quantite'],
                        $data['unite']);
                    if ($id) {
                        $apiService->deliverResponse(200, "Donnees modifiée avec succes.",$id);
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
            }else{
                $apiService->deliverResponse(400, "Champs type manquant ou mauvaise URL");
            }       
            break;
        default :
            $apiService->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
    use backend\Psr4AutoloaderClass;

    $loader = new Psr4AutoloaderClass;
    // register the autoloader
    $loader->register();
    // register the base directories for the namespace prefix
    $loader->addNamespace('backend\\', $_SERVER['DOCUMENT_ROOT']);

    use backend\Service\ApiService;
    use backend\Controleur\RecetteControleur;
    use backend\modele\RecetteCategorie;

    $apiService = ApiService::getInstance();
    $recetteControleur = RecetteControleur::getInstance();

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
            $duree = $segments[3] ?? null;
            $recherche = $segments[4] ?? null;
            $favori = $segments[5] ?? null;
            $specialite = $segments[6] ?? null;
            //byId
            if (isset($id )&& ctype_digit($id)) {
                $recette = $recetteControleur->laRecette($id);
                if ($recette){
                    $recette = $recetteControleur->ajouterNote($recette,$login);
                    $recette = $recette->toArray();
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$recette);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            }//recherche
            elseif(($id && !ctype_digit($id)) || $duree || $recherche || $favori || $specialite){
                $recettes = $recetteControleur->filtrerRecettes($groupe,$login,$id,$duree,$recherche,$favori,$specialite);
                if ($recettes){
                    $recettes = $apiService->toArrayList($recettes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$recettes);
                }else{
                    $apiService->deliverResponse(404, "Requete valide mais aucune donnée à récupérer");
                }
            //findAll
            }else{
                $recettes = $recetteControleur->toutesLesRecettesDuGroupe($groupe);
                if ($recettes){
                    $recette = $recetteControleur->ajouterNoteList($recettes,$login);
                    $recettes = $apiService->toArrayList($recettes);
                    $apiService->deliverResponse(200, "Donnée récupérée avec succès.",$recettes);
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
            if (empty($data['nom'])) $champsManquants[] = 'nom';
            if (empty($data['duree'])) $champsManquants[] = 'prenom';
            if (empty($data['categorie'])) $champsManquants[] = 'numeroDeLicence';
            if (empty($data['image'])) $champsManquants[] = 'dateDeNaissance';
            if (empty($data['groupe'])) $champsManquants[] = 'tailleEnCm'; 
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }
            if (RecetteCategorie::fromName($data['categorie']) === null) {
                $apiService->deliverResponse(400, "categorie invalide");
            }
            $id = $recetteControleur->ajouterRecette(
                $data['nom'],
                $data['duree'],
                RecetteCategorie::fromName($data['categorie']),
                $data['image'],
                $data['groupe']
            );
            if ($id) {
                $apiService->deliverResponse(200, "Donnees insérée avec succes.",$id);
            }else{
                $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
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
                try {
                    $statut = $recetteControleur->supprimerRecette($id);
                    if ($statut) {
                        $apiService->deliverResponse(200, "Donnees supprimée avec succes.");
                    }else{
                        $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                    }
                }catch (Exception $e){
                    $apiService->deliverResponse(400, $e->getMessage());
                }
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
            if (empty($data['duree'])) $champsManquants[] = 'duree';
            if (empty($data['categorie'])) $champsManquants[] = 'categorie';
            if (empty($data['image'])) $champsManquants[] = 'image';
            if (empty($data['groupe'])) $champsManquants[] = 'groupe'; 
            if (!empty($champsManquants)) {
                $message = "champs " . implode(', ',  $champsManquants) . " absent(s).";
                $apiService->deliverResponse(400, $message);
                break;
            }

            if (RecetteCategorie::fromName($data['categorie']) === null) {
                $apiService->deliverResponse(400, "categorie invalide");
                break;
            }

            $segments = explode('/', $_SERVER['REQUEST_URI']);
            $id = $segments[2] ?? null;
            if ($id && ctype_digit($id)) {
                $statut = $recetteControleur->modifierRecette(
                    $id,
                    $data['nom'],
                    $data['duree'],
                    RecetteCategorie::fromName($data['categorie']),
                    $data['image'],
                    $data['groupe']
                );
                if ($statut) {
                    $apiService->deliverResponse(200, "Donnees modifiée avec succes.",$data);
                }else{
                    $apiService->deliverResponse(400, "Données non insérées (problème inconnu)");
                }
            }else{
                $apiService->deliverResponse(400, "Champs RecetteId manquant");
                break;
            }
            break;
        default :
            $apiService->deliverResponse(400, "Syntaxe de la requête non conforme");
    }
?>
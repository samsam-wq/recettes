<?php

use \frontend\Controleur\RecetteControleur;

$recetteControleur = RecetteControleur::getInstance();

if (isset($_GET['delete'])){
    //TODO supprimer les dependances

    $reponse = $recetteControleur->supprimerRecette($_GET['delete']);
    if ($reponse['status_code']===200) {
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }else{
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }
}else{
    header('Location: /recettes');
    exit();
}
?>
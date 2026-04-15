<?php

use \frontend\Controleur\RecetteControleur;
use \frontend\Controleur\NoterControleur;
use \frontend\Controleur\EtapeControleur;

$recetteControleur = RecetteControleur::getInstance();
$noterControleur = NoterControleur::getInstance();
$etapeControleur = EtapeControleur::getInstance();

if (isset($_GET['delete'])){
    //TODO supprimer les dependances

    $reponse = $etapeControleur->supprimeretapesRecette($_GET['delete']);
    if ($reponse['status_code']!==200) {
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }

    $reponse = $noterControleur->supprimerNotesRecette($_GET['delete']);
    if ($reponse['status_code']!==200) {
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }

    $reponse = $recetteControleur->supprimerRecette($_GET['delete']);
    header('Location: /recettes?erreur='.$reponse['status_message']);
    exit();
    
}else{
    header('Location: /recettes');
    exit();
}
?>
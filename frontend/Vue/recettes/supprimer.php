<?php

use \frontend\Controleur\RecetteControleur;
use \frontend\Controleur\NoterControleur;
use \frontend\Controleur\EtapeControleur;
use \frontend\Controleur\UtiliseControleur;
use \frontend\Controleur\ContientControleur;

$contientControleur = ContientControleur::getInstance();
$utiliseControleur = UtiliseControleur::getInstance();
$recetteControleur = RecetteControleur::getInstance();
$noterControleur = NoterControleur::getInstance();
$etapeControleur = EtapeControleur::getInstance();

if (isset($_GET['delete'])){
    $reponse = $utiliseControleur->supprimerUtiliseRecette($_GET['delete']);
    if ($reponse['status_code']!==200) {
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }

    $reponse = $contientControleur->supprimerContientRecette($_GET['delete']);
    if ($reponse['status_code']!==200) {
        header('Location: /recettes?erreur='.$reponse['status_message']);
        exit();
    }

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
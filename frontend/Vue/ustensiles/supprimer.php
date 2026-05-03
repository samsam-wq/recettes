<?php

use \frontend\Controleur\UstensileControleur;

$ustensileControleur = UstensileControleur::getInstance();

if (isset($_GET['delete'])){
    $reponse = $ustensileControleur->supprimerUstensile($_GET['delete']);
    header('Location: /ustensiles?erreur='.$reponse['status_message']);
    exit();
}else{
    header('Location: /ustensiles');
    exit();
}
?>
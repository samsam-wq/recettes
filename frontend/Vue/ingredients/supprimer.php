<?php

use \frontend\Controleur\IngredientControleur;

$ingredientControleur = IngredientControleur::getInstance();

if (isset($_GET['delete'])){
    $reponse = $ingredientControleur->supprimerIngredient($_GET['delete']);
    header('Location: /ingredients?erreur='.$reponse['status_message']);
    exit();
}else{
    header('Location: /ingredients');
    exit();
}
?>
<?php
require('../../../modele/dao/bd/ConnexionBD.php');
require('../../../modele/Utilisateur.php');
require('../../../modele/dao/Dao.php');
require('../../../modele/dao/DaoUtilisateur.php');
if (isset($_GET['sid'])){
    session_id($_GET['sid']);
}
session_start();

$daoUtil = new DaoUtilisateur();

$user = $_POST['user'];
$pass = $_POST['pass'];

$utilisateur = $daoUtil->findById($user);

if ($utilisateur){
    $loginValide = $utilisateur->getLogin();
    $passValide = $utilisateur->getMdp();

    if ($user === $loginValide && $pass === $passValide) {
        $_SESSION['est_connecte'] = true;

        header('Location: ../../../vue/index.php');
        exit;
    } else {
        $_SESSION['erreur_login'] = "Identifiant ou mot de passe incorrect.";
        $_SESSION['est_connecte'] = false;
        header('Location: ../../../index.php?');
        exit;
    }
}else{
    $_SESSION['erreur_login'] = "Identifiant ou mot de passe incorrect.";
    $_SESSION['est_connecte'] = false;
    header('Location: ../../../index.php?');
    exit;
}
?>
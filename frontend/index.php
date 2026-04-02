<?php
session_start(); 
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
</head>
<body>

<div class="page-connexion">

    <div class="carte-connexion">

        <h1 class="titre">Nos recettes</h1>
        <p class="sous-titre">Je t'aime :)</p>
        <?php
            if (isset($_SESSION['erreur_login'])) {
                echo '<div class="msg-erreur">' . $_SESSION['erreur_login'] . '</div>';
            }
        ?>
        <form class="formulaire-connexion" method="post" action="controleur/redirection/login/GestionLogin.php">

            <div class="groupe-champ">
                <label>Nom d'utilisateur</label>
                <input type="text" name="user" required>
            </div>

            <div class="groupe-champ">
                <label>Mot de passe</label>
                <input type="password" name="pass" required>
            </div>

            <button type="submit" class="btn-connexion">Se connecter</button>
        </form>

    </div>

</div>

</body>
</html>
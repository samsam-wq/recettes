<?php
use frontend\Controleur\UtilisateurControleur;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $controleur = UtilisateurControleur::getInstance();

    $token = $controleur->seConnecter(trim($_POST["username"]), trim($_POST["password"]));
    if ($token) {
        $_SESSION['token'] = $token;
        $_SESSION['role'] = $controleur->getRole($token);
        $_SESSION['login'] = $controleur->getLogin($token);
        header("Location: /tableauDeBord");
        exit();
    } else {
        $erreur = "Le nom d'Utilisateur ou le mot de passe est incorrect";
    }
}
?>

<html lang="fr">
    <head>
        <title>R3.01</title>
        <meta name="viewport" content="width=device-width, initial-scale=1.0" charset="UTF-8"/>
        <link rel="stylesheet" href="../stylesheet.css"/>
        <link rel="icon" type="image/jpg" href="/favicon.jpg">
    </head>
    <body>
        <div class="CentredContainer">
            <h1>Login</h1>
            <div class="container">
                <form action="/login" method="post">
                    <div class="row">
                        <div class="col-20">
                            <label for="username">Username : </label>
                        </div>
                        <div class="col-80">
                            <input type="text" id="username" name="username"/><br> 
                        </div>
                    </div> 
                    <div class="row">
                        <div class="col-20">
                            <label for="password">Password : </label>
                        </div>
                        <div class="col-80">
                            <input type="password" id="pass" name="password"/><br>
                        </div>
                    </div>
                    <div class="row">
                        <input type="submit" value="Login"/>
                    </div>
                </form>
            </div>
            <p><?php if (isset($erreur)) { echo $erreur; } ?></p>
        </div>
    </body>
</html>

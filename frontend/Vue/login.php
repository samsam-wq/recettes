<?php
use frontend\Controleur\UtilisateurControleur;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["username"]) && isset($_POST["password"])) {
    $controleur = UtilisateurControleur::getInstance();

    $token = $controleur->seConnecter(trim($_POST["username"]), trim($_POST["password"]));
    if ($token) {
        $_SESSION['token'] = $token;
        $_SESSION['role']  = $controleur->getRole($token);
        $_SESSION['login'] = $controleur->getLogin($token);
        header("Location: /tableauDeBord");
        exit();
    } else {
        $erreur = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<div class="CentredContainer">

    <div style="text-align:center; margin-bottom:28px;">
        <div style="font-size:3rem; margin-bottom:10px;">🍳</div>
        <h1 style="font-family:var(--font-display); font-size:2rem; color:var(--clr-primary); letter-spacing:-.02em; margin:0;">
            Nos Recettes
        </h1>
        <p style="color:var(--clr-text-muted); font-size:.92rem; margin-top:6px;">
            Connecte-toi pour accéder à tes recettes
        </p>
    </div>

    <div class="container">
        <form action="/login" method="POST">

            <div class="row">
                <label for="username">Utilisateur</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Ton identifiant"
                    value="<?php echo htmlspecialchars($_POST['username'] ?? ''); ?>"
                    autocomplete="username"
                >
            </div>

            <div class="row">
                <label for="password">Mot de passe</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    autocomplete="current-password"
                >
            </div>

            <div class="row row--action">
                <input type="submit" value="Se connecter" class="largeSubmit">
            </div>

        </form>
    </div>

    <?php if (isset($erreur)) : ?>
        <p class="error-message"><?php echo htmlspecialchars($erreur); ?></p>
    <?php endif; ?>

</div>

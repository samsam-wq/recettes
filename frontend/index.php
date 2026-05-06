<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/Psr4AutoloaderClass.php';
use frontend\Psr4AutoloaderClass;

$loader = new Psr4AutoloaderClass;
$loader->register();
$loader->addNamespace('frontend\\', $_SERVER['DOCUMENT_ROOT']);

use frontend\Service\ApiService;
use frontend\Controleur\UtilisateurControleur;

$apiservice = ApiService::getInstance();

if (preg_match('/\.(?:png|jpg|jpeg|gif|ico|css|js)\??.*$/', $_SERVER["REQUEST_URI"])) {
    return false;
} else {

session_start();

if ($_SERVER["REQUEST_URI"] !== "/login" && !isset($_SESSION['token'])) {
    header('Location: /login');
    exit();
}
if ($_SERVER["REQUEST_URI"] !== "/login" && !$apiservice->isTokenValid($_SESSION['token'])) {
    header('Location: /login');
    exit();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Recettes</title>
    <link rel="stylesheet" href="/stylesheet.css">
    <link rel="icon" type="image/jpg" href="/favicon.jpg">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
</head>
<body>

<?php if ($_SERVER["REQUEST_URI"] !== '/login') : ?>

<header class="navbar">
    <div class="navbar-inner">

        <a href="/recettes" class="navbar-brand">
            <span class="navbar-brand-icon">🍳</span>
            <span class="navbar-brand-text">Nos Recettes</span>
        </a>

        <div class="dropdown">
            <button class="dropbtn">Recettes ▾</button>
            <div class="dropdown-content">
                <a href="/recettes">Toutes mes Recettes</a>
                <a href="/recettes/ajouter">➕ Ajouter une Recette</a>
                <a href="/recettesPublic">Toutes les Recettes</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Ingrédients ▾</button>
            <div class="dropdown-content">
                <a href="/ingredients">Tous les Ingrédients</a>
                <a href="/ingredients/ajouter">➕ Ajouter un Ingrédient</a>
            </div>
        </div>

        <div class="dropdown">
            <button class="dropbtn">Ustensiles ▾</button>
            <div class="dropdown-content">
                <a href="/ustensiles">Tous les Ustensiles</a>
                <a href="/ustensiles/ajouter">➕ Ajouter un Ustensile</a>
            </div>
        </div>

        <?php if (isset($_SESSION['login'])) : ?>
            <div class="nav-cta dropdown">
                <button class="dropbtn">👋 <?php echo htmlspecialchars($_SESSION['login']); ?></button>
                <div class="dropdown-content">
                    <a href="/">Inviter/Rejoindre</a>
                    <a href="/">Déconnexion</a>
                </div>
            </div>
        <?php endif; ?>

    </div>
</header>

<?php endif; ?>

<main class="main-content">
<?php
    $uri = strtok($_SERVER["REQUEST_URI"], '?');
    if ($uri !== "/") {
        require_once './Vue' . $uri . '.php';
    }
?>
</main>

<?php if ($_SERVER["REQUEST_URI"] !== '/login') : ?>
<footer class="site-footer">
    🍴 Nos Recettes &nbsp;·&nbsp; Usage personnel
</footer>
<?php endif; ?>

</body>
</html>
<?php } ?>

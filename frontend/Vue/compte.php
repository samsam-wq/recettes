<?php
/**
 * Vue/compte.php — Gestion du compte utilisateur
 *
 * Variables attendues du contrôleur :
 *   string       $login          — identifiant de l'utilisateur
 *   string|null  $groupe_code    — code d'invitation du groupe actuel
 *   string|null  $succes         — message de succès
 *   string|null  $erreur         — message d'erreur
 */

$login          = htmlspecialchars($_SESSION['login'] ?? '');
$groupe_code    = htmlspecialchars($_SESSION['groupe'] ?? '');
$succes         = $succes         ?? null;
$erreur         = $erreur         ?? null;

$initiale = strtoupper(mb_substr($login, 0, 1));

use frontend\Controleur\UtilisateurControleur;

$controleur = UtilisateurControleur::getInstance();

if (isset($_POST['modifierPassword'])) {
    if (trim($_POST["mdp_nouveau"])===trim($_POST["mdp_confirm"])) {
        $reponse = $controleur->modifierMdp($login,trim($_POST["mdp_actuel"]),trim($_POST["mdp_nouveau"]));
        if ($reponse['status_code']===200){
            $succes = $reponse['status_message'];
        }else{
            $erreur = $reponse['status_message'];
        }
    }
}elseif(isset($_POST['deconnexion'])){
    $etat = session_destroy();
    if ($etat){
        header("Location: /login");
        exit();
    }else{
        $erreur="impossible de détruire la session";
    }
}elseif(isset($_POST['supprimer'])){
    $reponse = $controleur->supprimerUtilisateur($login,$_POST['mdp_suppression']);
    if ($reponse['status_code']===200){
        header("Location: /login");
        exit();
    }else{
        $erreur = $reponse['status_message'];
    }
}elseif(isset($_POST['quitter'])){
    $reponse = $controleur->modifierGroupe($login,$_POST['mdp_quitter'],-1);
    if ($reponse['status_code']===200){
        $succes = $reponse['status_message'];
        $_SESSION['groupe']=$reponse['data'];
        $groupe_code    = htmlspecialchars($_SESSION['groupe'] ?? '');

        $token = $controleur->seConnecter($login,$_POST['mdp_quitter']);
        if ($token) {
            $_SESSION['token'] = $token;
            $_SESSION['groupe']  = $controleur->getGroupe($token);
            $_SESSION['login'] = $controleur->getLogin($token);
        }
    }else{
        $erreur = $reponse['status_message'];
    }
}

$lien = "http://frontend.test/login?groupe=".$groupe_code;
?>

<div class="compte-page">

    <nav class="breadcrumb">
        <a href="/recettes" class="breadcrumb-link">← Retour aux recettes</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Mon compte</span>
    </nav>

    <!-- ── Hero profil ─────────────────────────────────────── -->
    <div class="compte-hero">
        <div class="compte-avatar"><?= $initiale ?></div>
        <div class="compte-hero-info">
            <h1 class="compte-username"><?= $login ?></h1>
        </div>
    </div>

    <!-- ── Notifications ───────────────────────────────────── -->
    <?php if ($succes): ?>
    <div class="compte-notif compte-notif--success">
        <span>✅</span> <?= htmlspecialchars($succes) ?>
    </div>
    <?php endif; ?>

    <?php if ($erreur): ?>
    <div class="compte-notif compte-notif--error">
        <span>⚠️</span> <?= htmlspecialchars($erreur) ?>
    </div>
    <?php endif; ?>

    <!-- ── Grille ──────────────────────────────────────────── -->
    <div class="compte-grid">

        <!-- ── Modifier le mot de passe ──────────────── -->
            <section class="compte-card">
                <div class="compte-card-header">
                    <span class="compte-card-icon">🔒</span>
                    <div>
                        <h2 class="compte-card-title">Mot de passe</h2>
                        <p class="compte-card-subtitle">Modifier votre mot de passe</p>
                    </div>
                </div>
                <form method="POST" action="/compte" class="compte-form">
                    <div class="compte-field">
                        <label for="mdp_actuel">Mot de passe actuel</label>
                        <input type="password" id="mdp_actuel" name="mdp_actuel"
                               placeholder="••••••••" autocomplete="current-password" required>
                    </div>
                    <div class="compte-field">
                        <label for="mdp_nouveau">Nouveau mot de passe</label>
                        <input type="password" id="mdp_nouveau" name="mdp_nouveau"
                               placeholder="••••••••" autocomplete="new-password" required>
                    </div>
                    <div class="compte-field">
                        <label for="mdp_confirm">Confirmer</label>
                        <input type="password" id="mdp_confirm" name="mdp_confirm"
                               placeholder="••••••••" autocomplete="new-password" required>
                    </div>
                    <div class="compte-form-actions">
                        <button type="submit" name="modifierPassword" class="btn btn--primary">💾 Enregistrer</button>
                    </div>
                </form>
            </section>

        <!-- ══ COLONNE DROITE ════════════════════════════════ -->
        <div class="compte-col-right">
            <!-- ══ GROUPE ════════════════════════════════════════ -->
            <section class="compte-card">
                <div class="compte-card-header">
                    <span class="compte-card-icon">👥</span>
                    <div>
                        <h2 class="compte-card-title">Groupe de cuisine</h2>
                        <p class="compte-card-subtitle">Partagez vos recettes à deux</p>
                    </div>
                </div>
                <div class="compte-card-header">
                    <div>
                        <p class="compte-card-subtitle">Lien d'invitation :</p>
                        <a href="<?= htmlspecialchars($lien)  ?>"><p class="compte-card-subtitle"><?= htmlspecialchars($lien)  ?></p></a>
                    </div>
                </div>
                <div class="compte-card-header">
                    <form method="POST" action="/compte">
                        <label for="mdp_suppression">Mot de passe</label>
                        <input type="password" id="mdp_suppression" name="mdp_quitter"
                               placeholder="••••••••" required>
                        <button type="submit" name="quitter" class="btn btn--danger-solid"
                                onclick="return confirm('Quitter définitivement votre groupe ?')">
                            Quitter mon groupe
                        </button>
                    </form>
                </div>

                
            </section>

            <!-- ── Déconnexion ────────────────────────────── -->
            <section class="compte-card compte-card--warning">
                <div class="compte-card-header">
                    <span class="compte-card-icon">👋</span>
                    <div>
                        <h2 class="compte-card-title">Déconnexion</h2>
                        <p class="compte-card-subtitle">Fermer la session en cours</p>
                    </div>
                </div>
                <p class="compte-action-desc">
                    Vous serez redirigé vers la page de connexion. Vos recettes restent sauvegardées.
                </p>
                <form method="POST" action="/compte">
                    <button type="submit" name="deconnexion" class="btn btn--outline-warning"
                        onclick="return confirm('Supprimer définitivement cette recette ?')">
                        Se déconnecter</button>
                </form>
            </section>

            <!-- ── Supprimer le compte ────────────────────── -->
            <section class="compte-card compte-card--danger">
                <div class="compte-card-header">
                    <span class="compte-card-icon">🗑️</span>
                    <div>
                        <h2 class="compte-card-title">Supprimer mon compte</h2>
                        <p class="compte-card-subtitle">Action irréversible</p>
                    </div>
                </div>
                <div class="danger-warning">
                    <p class="danger-warning-text">
                        <strong>Attention :</strong> toutes vos recettes et données seront
                        définitivement supprimées. Cette action ne peut pas être annulée.
                    </p>
                </div>
                <form method="POST" action="/compte" class="compte-form">
                    <div class="compte-field">
                        <label for="confirm_suppression">
                            Tapez <strong><?= $login ?></strong> pour confirmer
                        </label>
                        <input type="text" id="confirm_suppression" name="confirm_suppression"
                               placeholder="<?= $login ?>" autocomplete="off" required>
                    </div>
                    <div class="compte-field">
                        <label for="mdp_suppression">Mot de passe</label>
                        <input type="password" id="mdp_suppression" name="mdp_suppression"
                               placeholder="••••••••" required>
                    </div>
                    <div class="compte-form-actions">
                        <button type="submit" name="supprimer" class="btn btn--danger-solid"
                                onclick="return confirm('Supprimer définitivement votre compte ?')">
                            🗑️ Supprimer mon compte
                        </button>
                    </div>
                </form>
            </section>

        </div>
    </div>
</div>

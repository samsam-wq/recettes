<?php
/**
 * Vue/recettes/ajouter.php — Formulaire d'ajout d'une recette
 *
 * Variables attendues du contrôleur :
 *   array|null $erreurs  — tableau de messages d'erreur
 *   array|null $old      — anciennes valeurs (re-population du form)
 */

use \frontend\Controleur\EtapeControleur;;

$etapeControleur = EtapeControleur::getInstance();

$erreurs = array();
$redirection = false;

if (
    isset($_POST['contenu']) && 
    isset($_POST['titre']) && 
    isset($_GET['numero']) && 
    isset($_GET['id'])
){
    $reponse = $etapeControleur->ajouterEtape($_POST['titre'],$_POST['contenu'],$_GET['numero'],$_GET['id']);
    if ($reponse['status_code'] === 201){
        if (isset($_POST['recette'])){
            header('Location: /recettes/modifier?id='.$_GET['id']);
            exit();
        }elseif (isset($_POST['etape'])){
            header('Location: /recettes/ajouterEtape?id='.$_GET['id'].'&numero='.($_GET['numero']+1));
            exit();
        }
    }else{
        $erreurs[] = $reponse['status_message'];
        $old['contenu'] = $_POST['contenu'];
        $old['titre'] = $_POST['titre'];
        $old['numero'] = $_GET['numero'];
        $old['id'] = $_GET['id'];
    }
}

$old    = $old    ?? [];
$erreurs = $erreurs ?? [];

function oldVal(array $old, string $key, string $default = ''): string {
    return htmlspecialchars($old[$key] ?? $default);
}
?>

<div class="form-page">

    <nav class="breadcrumb">
        <a href="/recettes" class="breadcrumb-link">← Toutes les recettes</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Nouvelle étape</span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">➕ Nouvelle étape</h1>
        <p class="form-page-subtitle">Ajoute une étape à la recette.</p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST" action="/recettes/ajouterEtape?id=<?= $_GET['id'] ?>&numero=<?= $_GET['numero'] ?>" enctype="multipart/form-data">

        <!-- ── Étapes ─────────────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📋 Étapes de préparation</h2>
            <p class="form-card-hint">Décris chaque étape dans l'ordre.</p>

            <div class="dynamic-list" id="etapes-list">
                <div class="dynamic-row dynamic-row--etape">
                    <span class="etape-num-badge"><?= $_GET['numero'] ?></span>
                    <div class="dynamic-row-inputs dynamic-row-inputs--etape">
                        <input type="text" name="titre"
                               placeholder="Titre de l'étape (ex : Préchauffer le four)"
                               value="<?= htmlspecialchars(oldVal($old, 'titre'))?>">
                        <textarea name="contenu"
                                  placeholder="Description détaillée de cette étape…">
                                  <?= htmlspecialchars(oldVal($old, 'contenu') ) ?></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/recettes" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="recette" value="1" class="btn btn--launch">
                Retour à la recette
            </button>
            <button type="submit" name="etape" value="1" class="btn btn--launch">
                Ajouter une autre étape
            </button>
        </div>

    </form>
</div>

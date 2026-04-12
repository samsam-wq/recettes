<?php
/**
 * Vue/recettes/ajouter.php — Formulaire d'ajout d'une recette
 *
 * Variables attendues du contrôleur :
 *   array|null $erreurs  — tableau de messages d'erreur
 *   array|null $old      — anciennes valeurs (re-population du form)
 */

use \frontend\Controleur\RecetteControleur;

$recetteControleur = RecetteControleur::getInstance();

if (
    isset($_POST['nom']) && 
    isset($_POST['duree']) && 
    isset($_POST['categorie']) &&
    isset($_POST['image']) 
){
    $reponse = $recetteControleur->ajouterRecette(
        $_POST['nom'],
        $_POST['duree'],
        $_POST['categorie'],
        $_POST['image'],
        $_SESSION['groupe']
    );
    if ($reponse['status_code'] === 201){
        $id = $reponse['data'];
        header('Location: /recettes/ajouterEtape?id='.$id);
        exit();
    }else{
        $erreurs = array($reponse['status_message']);
        $old['nom'] = $_POST['nom'];
        $old['duree'] =$_POST['duree'];
        $old['categorie'] = $_POST['categorie'];
        $old['image'] = $_POST['image'];
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
        <span class="breadcrumb-current">Nouvelle recette</span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">➕ Nouvelle recette</h1>
        <p class="form-page-subtitle">Ajoute une recette à votre collection.</p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST" action="/recettes/ajouter" enctype="multipart/form-data">

        <!-- ── Infos générales ────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📝 Informations générales</h2>

            <div class="row">
                <label for="nom">Nom de la recette <span class="required">*</span></label>
                <input type="text" id="nom" name="nom"
                       placeholder="Ex : Tarte tatin aux pommes"
                       value="<?= oldVal($old, 'nom') ?>" required>
            </div>

            <div class="row">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="">— Choisir —</option>
                    <?php foreach (['Dessert', 'Plat', 'Végétarien', 'Entrée', 'Soupe', 'Autre'] as $c): ?>
                    <option value="<?= $c ?>" <?= oldVal($old, 'categorie') === $c ? 'selected' : '' ?>>
                        <?= $c ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <label for="duree">Durée (minutes)</label>
                <input type="number" id="duree" name="duree" min="1" max="600"
                       placeholder="Ex : 45"
                       value="<?= oldVal($old, 'duree') ?>">
            </div>

            <div class="row">
                <label for="image">Image (URL)</label>
                <input type="text" id="image" name="image"
                       placeholder="https://…"
                       value="<?= oldVal($old, 'image') ?>">
            </div>
        </div>

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/recettes" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="Recette" class="btn btn--launch">
                Ajouter une étape
            </button>
        </div>

    </form>
</div>

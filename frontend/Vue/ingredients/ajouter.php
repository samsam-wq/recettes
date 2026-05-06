<?php
/**
 * Vue/recettes/ajouter.php — Formulaire d'ajout d'une recette
 *
 * Variables attendues du contrôleur :
 *   array|null $erreurs  — tableau de messages d'erreur
 *   array|null $old      — anciennes valeurs (re-population du form)
 * 
 */

use \frontend\Controleur\IngredientControleur;

$ingredientControleur = IngredientControleur::getInstance();

$erreurs = array();

if (isset($_POST['nom'])){
    $reponse = $ingredientControleur->ajouterIngredient($_POST['nom']);
    if ($reponse['status_code'] === 201){
        header('Location: /ustensiles');
        exit();
    }else{
        $erreurs[] = $reponse['status_message'];
        $old['nom'] = $_POST['nom'];
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
        <a href="/ustensiles" class="breadcrumb-link">← Touts les ingrédients</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Nouvel Ustensile</span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">➕ Nouvel Ingrédient</h1>
        <p class="form-page-subtitle">Ajoute un Ingrédient à votre collection.</p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST" action="/ustensiles/ajouter" enctype="multipart/form-data">

        <!-- ── Infos générales ────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📝 Informations générales</h2>

            <div class="row">
                <label for="nom">Nom <span class="required">*</span></label>
                <input type="text" id="nom" name="nom"
                       placeholder="Ex : Couteau"
                       value="<?= oldVal($old, 'nom') ?>" required>
            </div>
        </div>

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/ustensiles" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="Recette" class="btn btn--launch">
                Ajouter l'ingrédient
            </button>
        </div>

    </form>
</div>

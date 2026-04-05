<?php
/**
 * Vue/recettes/ajouter.php — Formulaire d'ajout d'une recette
 *
 * Variables attendues du contrôleur :
 *   array|null $erreurs  — tableau de messages d'erreur
 *   array|null $old      — anciennes valeurs (re-population du form)
 */

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
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          placeholder="Une courte description appétissante…"><?= oldVal($old, 'description') ?></textarea>
            </div>

            <div class="row">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="">— Choisir —</option>
                    <?php foreach (['Dessert', 'Plat principal', 'Végétarien', 'Entrée', 'Soupe', 'Autre'] as $c): ?>
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
                <label for="personnes">Nombre de personnes</label>
                <input type="number" id="personnes" name="personnes" min="1" max="50"
                       placeholder="Ex : 4"
                       value="<?= oldVal($old, 'personnes') ?>">
            </div>

            <div class="row">
                <label for="image">Image (URL)</label>
                <input type="text" id="image" name="image"
                       placeholder="https://…"
                       value="<?= oldVal($old, 'image') ?>">
            </div>
        </div>

        <!-- ── Ingrédients ────────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">🧂 Ingrédients</h2>
            <p class="form-card-hint">Ajoute autant de lignes que nécessaire.</p>

            <div class="dynamic-list" id="ingredients-list">
                <div class="dynamic-row">
                    <div class="dynamic-row-inputs">
                        <input type="text" name="ingredients[0][nom]"
                               placeholder="Ingrédient" class="input-ingredient-nom"
                               value="<?= oldVal($old, 'ingredients[0][nom]') ?>">
                        <input type="text" name="ingredients[0][quantite]"
                               placeholder="Quantité" class="input-ingredient-qty"
                               value="<?= oldVal($old, 'ingredients[0][quantite]') ?>">
                        <input type="text" name="ingredients[0][unite]"
                               placeholder="Unité (g, ml…)" class="input-ingredient-unit"
                               value="<?= oldVal($old, 'ingredients[0][unite]') ?>">
                    </div>
                </div>

                <?php
                // Re-population en cas d'erreur (à partir de l'index 1)
                if (!empty($old['ingredients'])):
                    foreach (array_slice($old['ingredients'], 1, null, true) as $idx => $ing): ?>
                <div class="dynamic-row">
                    <div class="dynamic-row-inputs">
                        <input type="text" name="ingredients[<?= $idx ?>][nom]"
                               placeholder="Ingrédient" class="input-ingredient-nom"
                               value="<?= htmlspecialchars($ing['nom'] ?? '') ?>">
                        <input type="text" name="ingredients[<?= $idx ?>][quantite]"
                               placeholder="Quantité" class="input-ingredient-qty"
                               value="<?= htmlspecialchars($ing['quantite'] ?? '') ?>">
                        <input type="text" name="ingredients[<?= $idx ?>][unite]"
                               placeholder="Unité" class="input-ingredient-unit"
                               value="<?= htmlspecialchars($ing['unite'] ?? '') ?>">
                    </div>
                </div>
                    <?php endforeach;
                endif; ?>
            </div>

            <!-- Ajout d'une ligne via un submit séparé (sans JS) -->
            <button type="submit" name="add_ingredient" value="1"
                    class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter un ingrédient
            </button>
        </div>

        <!-- ── Étapes ─────────────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📋 Étapes de préparation</h2>
            <p class="form-card-hint">Décris chaque étape dans l'ordre.</p>

            <div class="dynamic-list" id="etapes-list">
                <div class="dynamic-row dynamic-row--etape">
                    <span class="etape-num-badge">1</span>
                    <div class="dynamic-row-inputs dynamic-row-inputs--etape">
                        <input type="text" name="etapes[0][titre]"
                               placeholder="Titre de l'étape (ex : Préchauffer le four)"
                               value="<?= oldVal($old, 'etapes[0][titre]') ?>">
                        <textarea name="etapes[0][description]"
                                  placeholder="Description détaillée de cette étape…"><?= oldVal($old, 'etapes[0][description]') ?></textarea>
                    </div>
                </div>

                <?php
                if (!empty($old['etapes'])):
                    foreach (array_slice($old['etapes'], 1, null, true) as $idx => $etape): ?>
                <div class="dynamic-row dynamic-row--etape">
                    <span class="etape-num-badge"><?= $idx + 1 ?></span>
                    <div class="dynamic-row-inputs dynamic-row-inputs--etape">
                        <input type="text" name="etapes[<?= $idx ?>][titre]"
                               placeholder="Titre de l'étape"
                               value="<?= htmlspecialchars($etape['titre'] ?? '') ?>">
                        <textarea name="etapes[<?= $idx ?>][description]"
                                  placeholder="Description…"><?= htmlspecialchars($etape['description'] ?? '') ?></textarea>
                    </div>
                </div>
                    <?php endforeach;
                endif; ?>
            </div>

            <button type="submit" name="add_etape" value="1"
                    class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter une étape
            </button>
        </div>

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/recettes" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="1" class="btn btn--launch">
                💾 Enregistrer la recette
            </button>
        </div>

    </form>
</div>

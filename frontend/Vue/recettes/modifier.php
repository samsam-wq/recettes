<?php
/**
 * Vue/recettes/modifier.php — Formulaire de modification d'une recette
 *
 * Variables attendues du contrôleur :
 *   array      $recette  — données actuelles de la recette
 *   array|null $erreurs  — messages d'erreur de validation
 *   array|null $old      — anciennes valeurs soumises (priorité sur $recette)
 */

if (empty($recette)) {
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Recette introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$erreurs = $erreurs ?? [];
// $old prend la priorité sur $recette pour re-peupler après une erreur de validation
$data = array_merge($recette, $old ?? []);

$id          = (int)$recette['id'];
$ingredients = $data['ingredients'] ?? $recette['ingredients'] ?? [['nom' => '', 'quantite' => '', 'unite' => '']];
$etapes      = $data['etapes']      ?? $recette['etapes']      ?? [['titre' => '', 'description' => '']];

function modVal(array $data, string $key, string $default = ''): string {
    return htmlspecialchars($data[$key] ?? $default);
}
?>

<div class="form-page">

    <nav class="breadcrumb">
        <a href="/recettes" class="breadcrumb-link">← Toutes les recettes</a>
        <span class="breadcrumb-sep">/</span>
        <a href="/recettes/<?= $id ?>" class="breadcrumb-link"><?= htmlspecialchars($recette['nom'] ?? '') ?></a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Modifier</span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">✏️ Modifier la recette</h1>
        <p class="form-page-subtitle"><?= htmlspecialchars($recette['nom'] ?? '') ?></p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST"
          action="/recettes/<?= $id ?>/modifier" enctype="multipart/form-data">

        <input type="hidden" name="id" value="<?= $id ?>">

        <!-- ── Infos générales ────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📝 Informations générales</h2>

            <div class="row">
                <label for="nom">Nom de la recette <span class="required">*</span></label>
                <input type="text" id="nom" name="nom"
                       placeholder="Nom de la recette"
                       value="<?= modVal($data, 'nom') ?>" required>
            </div>

            <div class="row">
                <label for="description">Description</label>
                <textarea id="description" name="description"
                          placeholder="Une courte description…"><?= modVal($data, 'description') ?></textarea>
            </div>

            <div class="row">
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="">— Choisir —</option>
                    <?php foreach (['Dessert', 'Plat principal', 'Végétarien', 'Entrée', 'Soupe', 'Autre'] as $c): ?>
                    <option value="<?= $c ?>" <?= modVal($data, 'categorie') === $c ? 'selected' : '' ?>>
                        <?= $c ?>
                    </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="row">
                <label for="duree">Durée (minutes)</label>
                <input type="number" id="duree" name="duree" min="1" max="600"
                       value="<?= modVal($data, 'duree') ?>">
            </div>

            <div class="row">
                <label for="personnes">Nombre de personnes</label>
                <input type="number" id="personnes" name="personnes" min="1" max="50"
                       value="<?= modVal($data, 'personnes') ?>">
            </div>

            <div class="row">
                <label for="image">Image (URL)</label>
                <input type="text" id="image" name="image"
                       placeholder="https://…"
                       value="<?= modVal($data, 'image') ?>">
                <?php if (!empty($recette['image'])): ?>
                <div style="grid-column:2; margin-top:8px;">
                    <img src="<?= htmlspecialchars($recette['image']) ?>"
                         alt="Aperçu" class="image-preview">
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── Ingrédients ────────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">🧂 Ingrédients</h2>

            <div class="dynamic-list">
                <?php foreach ($ingredients as $idx => $ing): ?>
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
                    <!-- Suppression d'une ligne via submit (sans JS) -->
                    <button type="submit" name="remove_ingredient" value="<?= $idx ?>"
                            class="btn-remove" formnovalidate title="Supprimer">✕</button>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="add_ingredient" value="1"
                    class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter un ingrédient
            </button>
        </div>

        <!-- ── Étapes ─────────────────────────────────────────── -->
        <div class="form-card">
            <h2 class="form-card-title">📋 Étapes de préparation</h2>

            <div class="dynamic-list">
                <?php foreach ($etapes as $idx => $etape): ?>
                <div class="dynamic-row dynamic-row--etape">
                    <span class="etape-num-badge"><?= $idx + 1 ?></span>
                    <div class="dynamic-row-inputs dynamic-row-inputs--etape">
                        <input type="text" name="etapes[<?= $idx ?>][titre]"
                               placeholder="Titre de l'étape"
                               value="<?= htmlspecialchars($etape['titre'] ?? '') ?>">
                        <textarea name="etapes[<?= $idx ?>][description]"
                                  placeholder="Description…"><?= htmlspecialchars($etape['description'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" name="remove_etape" value="<?= $idx ?>"
                            class="btn-remove" formnovalidate title="Supprimer">✕</button>
                </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" name="add_etape" value="1"
                    class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter une étape
            </button>
        </div>

        <!-- ── Zone danger ────────────────────────────────────── -->
        <div class="form-card form-card--danger">
            <h2 class="form-card-title">⚠️ Zone dangereuse</h2>
            <p style="color:var(--clr-text-muted); font-size:.9rem; margin-bottom:16px;">
                La suppression est définitive et irréversible.
            </p>
            <button type="submit" name="delete" value="1"
                    class="btn btn--danger-solid" formnovalidate
                    onclick="return confirm('Supprimer définitivement cette recette ?')">
                🗑️ Supprimer la recette
            </button>
        </div>

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/recettes/<?= $id ?>" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="1" class="btn btn--launch">
                💾 Enregistrer les modifications
            </button>
        </div>

    </form>
</div>

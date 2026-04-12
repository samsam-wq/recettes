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

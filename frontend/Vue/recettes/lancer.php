<?php
/**
 * Vue/recettes/lancer.php — Mode "Lancer la recette" pas à pas
 *
 * Technique CSS pur :
 *   On utilise des radio buttons cachés + le sélecteur :checked ~ .steps .step
 *   pour afficher une seule étape à la fois, sans JS.
 *
 * Variables attendues du contrôleur :
 *   array $recette  — ['id', 'nom', 'image', 'duree', 'personnes',
 *                       'etapes' => [['ordre','titre','description'], …],
 *                       'ingredients' => [['nom','quantite','unite'], …]]
 */

if (empty($recette) || empty($recette['etapes'])) {
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Aucune étape disponible pour cette recette.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$id        = (int) $recette['id'];
$nom       = htmlspecialchars($recette['nom']      ?? '');
$duree     = htmlspecialchars($recette['duree']    ?? '');
$personnes = (int)($recette['personnes']           ?? 0);
$etapes    = $recette['etapes'];
$nEtapes   = count($etapes);
$ingredients = $recette['ingredients'] ?? [];
?>

<!-- ── CSS spécifique au mode pas-à-pas ────────────────────── -->
<style>
/* On cache tous les panels d'étapes par défaut */
.step-panel { display: none; }

/* Affiche le panel correspondant au radio coché */
<?php for ($i = 1; $i <= $nEtapes; $i++): ?>
#step-radio-<?= $i ?>:checked ~ .launcher-body .step-panel-<?= $i ?> { display: flex; }
#step-radio-<?= $i ?>:checked ~ .launcher-nav .step-nav-btn[data-step="<?= $i ?>"] {
    background-color: var(--clr-primary);
    color: #fff;
    border-color: var(--clr-primary);
}
#step-radio-<?= $i ?>:checked ~ .launcher-progress .progress-fill {
    width: <?= round(($i / $nEtapes) * 100) ?>%;
}
<?php endfor; ?>

/* Masque les boutons Précédent/Suivant selon l'étape active */
<?php for ($i = 1; $i <= $nEtapes; $i++): ?>
#step-radio-<?= $i ?>:checked ~ .launcher-body .step-panel-<?= $i ?> .btn-prev-<?= $i ?>,
#step-radio-<?= $i ?>:checked ~ .launcher-body .step-panel-<?= $i ?> .btn-next-<?= $i ?> {
    display: inline-flex;
}
<?php endfor; ?>
/* Masque le bouton Précédent sur étape 1 */
#step-radio-1:checked ~ .launcher-body .step-panel-1 .btn-prev-1 { display: none !important; }
/* Masque le bouton Suivant sur la dernière étape */
#step-radio-<?= $nEtapes ?>:checked ~ .launcher-body .step-panel-<?= $nEtapes ?> .btn-next-<?= $nEtapes ?> {
    display: none !important;
}
</style>

<!-- ── Radios cachés (état de la navigation) ─────────────────── -->
<?php for ($i = 1; $i <= $nEtapes; $i++): ?>
<input type="radio" name="step" id="step-radio-<?= $i ?>"
       class="step-radio" <?= $i === 1 ? 'checked' : '' ?>>
<?php endfor; ?>

<div class="launcher-page">

    <!-- En-tête -->
    <div class="launcher-header">
        <a href="/recettes/<?= $id ?>" class="launcher-back">← Retour à la recette</a>
        <div class="launcher-title-wrap">
            <h1 class="launcher-title"><?= $nom ?></h1>
            <div class="launcher-meta">
                <?php if ($duree): ?><span>⏱ <?= $duree ?> min</span><?php endif; ?>
                <?php if ($personnes): ?><span>👤 <?= $personnes ?> pers.</span><?php endif; ?>
                <span>📋 <?= $nEtapes ?> étape<?= $nEtapes > 1 ? 's' : '' ?></span>
            </div>
        </div>
    </div>

    <!-- Barre de progression -->
    <div class="launcher-progress">
        <div class="progress-track">
            <div class="progress-fill"></div>
        </div>
        <span class="progress-label">
            <?php for ($i = 1; $i <= $nEtapes; $i++): ?>
            <span class="progress-step-label progress-label-<?= $i ?>">
                Étape <?= $i ?> / <?= $nEtapes ?>
            </span>
            <?php endfor; ?>
        </span>
    </div>

    <!-- Numéros d'étapes (navigation) -->
    <nav class="launcher-nav">
        <?php for ($i = 1; $i <= $nEtapes; $i++): ?>
        <label for="step-radio-<?= $i ?>" class="step-nav-btn" data-step="<?= $i ?>"
               title="Étape <?= $i ?>">
            <?= $i ?>
        </label>
        <?php endfor; ?>
    </nav>

    <!-- Corps : panels d'étapes -->
    <div class="launcher-body">

        <?php foreach ($etapes as $i => $etape):
            $num = $i + 1;
            $titre = htmlspecialchars($etape['titre']       ?? "Étape $num");
            $desc  = htmlspecialchars($etape['description'] ?? '');
        ?>
        <div class="step-panel step-panel-<?= $num ?>">

            <!-- Colonne principale : étape -->
            <div class="step-main">
                <div class="step-badge"><?= $num ?> / <?= $nEtapes ?></div>
                <h2 class="step-title"><?= $titre ?></h2>
                <p class="step-desc"><?= nl2br($desc) ?></p>

                <!-- Navigation Précédent / Suivant -->
                <div class="step-nav-controls">
                    <?php if ($num > 1): ?>
                    <label for="step-radio-<?= $num - 1 ?>"
                           class="btn btn--ghost btn-prev-<?= $num ?>">
                        ← Étape précédente
                    </label>
                    <?php endif; ?>

                    <?php if ($num < $nEtapes): ?>
                    <label for="step-radio-<?= $num + 1 ?>"
                           class="btn btn--primary btn-next-<?= $num ?>">
                        Étape suivante →
                    </label>
                    <?php else: ?>
                    <!-- Dernière étape : bouton terminer -->
                    <a href="/recettes/<?= $id ?>" class="btn btn--launch">
                        ✅ Recette terminée !
                    </a>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Colonne latérale : ingrédients (rappel) -->
            <?php if (!empty($ingredients)): ?>
            <aside class="step-sidebar">
                <h3 class="step-sidebar-title">🧂 Ingrédients</h3>
                <ul class="step-ingredients">
                    <?php foreach ($ingredients as $ing): ?>
                    <li class="step-ingredient">
                        <span><?= htmlspecialchars($ing['nom'] ?? '') ?></span>
                        <span class="step-ingredient-qty">
                            <?= htmlspecialchars($ing['quantite'] ?? '') ?>
                            <?= htmlspecialchars($ing['unite']    ?? '') ?>
                        </span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </aside>
            <?php endif; ?>

        </div><!-- /.step-panel -->
        <?php endforeach; ?>

    </div><!-- /.launcher-body -->

</div><!-- /.launcher-page -->

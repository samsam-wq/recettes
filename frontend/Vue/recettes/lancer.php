<?php
use \frontend\Controleur\RecetteControleur;
use \frontend\Controleur\EtapeControleur;

$recetteControleur = RecetteControleur::getInstance();
$etapeControleur   = EtapeControleur::getInstance();

$reponse = $recetteControleur->laRecette($_GET['id'] ?? null);
if ($reponse['status_code'] === 200) {
    $recette = $reponse['data'];

    $etapes = $etapeControleur->lesEtapesDuPlat($_GET['id']);
    if ($etapes['status_code'] === 200) {
        $etapes = $etapes['data'];
    } else {
        $etapes = null;
    }
} else {
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Recette introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$id          = (int) $_GET['id'];
$nom         = htmlspecialchars($recette['nom']   ?? '');
$duree       = htmlspecialchars($recette['duree'] ?? '');
$nEtapes     = count($etapes ?? []);
$ingredients = $recette['ingredients'] ?? [];
?>

<style>
.step-panel { display: none; }

<?php for ($i = 1; $i <= $nEtapes; $i++): ?>
.launcher-page:has(#step-radio-<?= $i ?>:checked) .step-panel-<?= $i ?> { display: flex; }
.launcher-page:has(#step-radio-<?= $i ?>:checked) .step-nav-btn[data-step="<?= $i ?>"] {
    background-color: var(--clr-primary);
    color: #fff;
    border-color: var(--clr-primary);
}
.launcher-page:has(#step-radio-<?= $i ?>:checked) .progress-fill {
    width: <?= round(($i / $nEtapes) * 100) ?>%;
}
.launcher-page:has(#step-radio-<?= $i ?>:checked) .progress-label-<?= $i ?> { display: inline; }
<?php endfor; ?>

.progress-step-label { display: none; }
#step-radio-1:checked ~ .launcher-body .step-panel-1 .btn-prev-1 { display: none !important; }
#step-radio-<?= $nEtapes ?>:checked ~ .launcher-body .step-panel-<?= $nEtapes ?> .btn-next-<?= $nEtapes ?> { display: none !important; }
</style>

<div class="launcher-page">

    <!-- ✅ Radios DANS le conteneur pour que :has() fonctionne -->
    <?php for ($i = 1; $i <= $nEtapes; $i++): ?>
    <input type="radio" name="step" id="step-radio-<?= $i ?>"
           class="step-radio" <?= $i === 1 ? 'checked' : '' ?>>
    <?php endfor; ?>

    <div class="launcher-header">
        <a href="/recettes/detail?id=<?= $id ?>" class="launcher-back">← Retour à la recette</a>
        <div class="launcher-title-wrap">
            <h1 class="launcher-title"><?= $nom ?></h1>
            <div class="launcher-meta">
                <?php if ($duree): ?><span>⏱ <?= $duree ?> min</span><?php endif; ?>
                <span>📋 <?= $nEtapes ?> étape<?= $nEtapes > 1 ? 's' : '' ?></span>
            </div>
        </div>
    </div>

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

    <nav class="launcher-nav">
        <?php for ($i = 1; $i <= $nEtapes; $i++): ?>
        <label for="step-radio-<?= $i ?>" class="step-nav-btn" data-step="<?= $i ?>"
               title="Étape <?= $i ?>">
            <?= $i ?>
        </label>
        <?php endfor; ?>
    </nav>

    <div class="launcher-body">
        <?php foreach ($etapes as $i => $etape):
            $num   = $i + 1;
            $titre = htmlspecialchars($etape['titre']   ?? "Étape $num");
            $desc  = htmlspecialchars($etape['contenu'] ?? ''); // ✅ 'contenu' et non 'description'
        ?>
        <div class="step-panel step-panel-<?= $num ?>">

            <div class="step-main">
                <div class="step-badge"><?= $num ?> / <?= $nEtapes ?></div>
                <h2 class="step-title"><?= $titre ?></h2>
                <p class="step-desc"><?= nl2br($desc) ?></p>

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
                    <a href="/recettes/detail?id=<?= $id ?>" class="btn btn--launch"> <!-- ✅ URL corrigée -->
                        ✅ Recette terminée !
                    </a>
                    <?php endif; ?>
                </div>
            </div>

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

        </div>
        <?php endforeach; ?>
    </div>

</div>
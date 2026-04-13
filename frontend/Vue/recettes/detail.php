<?php
/**
 * Vue/recettes/detail.php — Fiche détail d'une recette
 *
 * Variables attendues du contrôleur :
 *   array  $recette   — données de la recette
 *                        ['id', 'nom', 'image', 'categorie', 'duree',
 *                         'personnes', 'description', 'favori',
 *                         'ingredients' => [['nom', 'quantite', 'unite'], …],
 *                         'etapes'      => [['ordre', 'titre', 'description'], …]]
 */

use \frontend\Controleur\RecetteControleur;
use \frontend\Controleur\NoterControleur;

$recetteControleur = RecetteControleur::getInstance();
$noterControleur = NoterControleur::getInstance();

if (isset($_GET['id'])){
    $id = $_GET['id'];
}

if (isset($_GET['idFav'])){
    $id = $_GET['idFav'];
    $reponse = $noterControleur->mettreOuEnleverEnfavori($_GET['idFav']);
    if ($reponse['status_code']!=200) {
        $erreur = $reponse['status_message'];
    }
}

if (isset($_GET['idSpe'])){
    $id = $_GET['idSpe'];
    $reponse = $noterControleur->mettreOuEnleverSpecialite($_GET['idSpe']);
    if ($reponse['status_code']!=200) {
        $erreur = $reponse['status_message'];
    }
}

if (isset($id)){
    $reponse = $recetteControleur->laRecette($id);
}else{
    $reponse = $recetteControleur->getRecetteAleatoire();
}

if ($reponse['status_code']===200) {
    $recette = $reponse['data'];
}else{
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Recette introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$id        = (int) $recette['Id_recette'];
$nom       = htmlspecialchars($recette['nom']         ?? '');
$categorie = htmlspecialchars($recette['categorie']   ?? '');
$duree     = htmlspecialchars($recette['duree']       ?? '');
$personnes = (int)($recette['personnes']              ?? 0);
$desc      = htmlspecialchars($recette['description'] ?? '');
$image     = htmlspecialchars($recette['image']       ?? '');
$favori    = ( $recette['notes']!==null && !empty($recette['notes']['favori']));
$specialite    = ( $recette['notes']!==null && !empty($recette['notes']['specialite']));
$ingredients = $recette['ingredients'] ?? [];
$etapes      = $recette['etapes']      ?? [];

$catClass = match(strtolower($recette['categorie'] ?? '')) {
    'plat', 'plat principal'  => 'card-category--main',
    'végétarien', 'vegetarien' => 'card-category--veg',
    default => ''
};
?>

<div class="detail-page">

    <!-- ── Fil d'Ariane ───────────────────────────────────────── -->
    <nav class="breadcrumb">
        <a href="/recettes" class="breadcrumb-link">← Toutes les recettes</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current"><?= $nom ?></span>
    </nav>

    <!-- ── Hero recette ───────────────────────────────────────── -->
    <div class="detail-hero">
        <div class="detail-hero-image-wrap">
            <?php if ($image): ?>
                <img src="<?= $image ?>" alt="<?= $nom ?>" class="detail-hero-image">
            <?php else: ?>
                <div class="detail-hero-image detail-hero-image--placeholder">🍽️</div>
            <?php endif; ?>
            <span class="card-category <?= $catClass ?>"><?= $categorie ?></span>
        </div>

        <div class="detail-hero-info">
            <h1 class="detail-title"><?= $nom ?></h1>

            <?php if ($desc): ?>
                <p class="detail-desc"><?= $desc ?></p>
            <?php endif; ?>

            <!-- Méta -->
            <div class="detail-meta-row">
                <?php if ($duree): ?>
                <div class="detail-meta-card">
                    <span class="detail-meta-icon">⏱</span>
                    <span class="detail-meta-value"><?= $duree ?> min</span>
                    <span class="detail-meta-label">Durée</span>
                </div>
                <?php endif; ?>
                <?php if (!empty($etapes)): ?>
                <div class="detail-meta-card">
                    <span class="detail-meta-icon">📋</span>
                    <span class="detail-meta-value"><?= count($etapes) ?></span>
                    <span class="detail-meta-label">Étapes</span>
                </div>
                <?php endif; ?>
                <?php if (!empty($ingredients)): ?>
                <div class="detail-meta-card">
                    <span class="detail-meta-icon">🧂</span>
                    <span class="detail-meta-value"><?= count($ingredients) ?></span>
                    <span class="detail-meta-label">Ingrédients</span>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="detail-actions">
                <?php if (!empty($etapes)): ?>
                <a href="/recettes/lancer?id=<?= $id ?>" class="btn btn--launch">
                    ▶ Lancer la recette
                </a>
                <?php endif; ?>

                <form method="POST" action="/recettes/detail?idFav=<?=(int) $id ?>" style="display:inline;">
                    <input type="hidden" name="recette_id" value="<?= $id ?>">
                    <input type="hidden" name="action"     value="toggle">
                    <input type="hidden" name="redirect"   value="/recettes?id=<?= $id ?>">
                    <button type="submit" class="btn btn--fav <?= $favori ? 'btn--fav-active' : '' ?>">
                        <?= $favori ? '❤️ Favori' : '🤍 Ajouter aux favoris' ?>
                    </button>
                </form>

                <form method="POST" action="/recettes/detail?idSpe=<?=(int) $id ?>" style="display:inline;">
                    <input type="hidden" name="recette_id" value="<?= $id ?>">
                    <input type="hidden" name="action"     value="toggle">
                    <input type="hidden" name="redirect"   value="/recettes?id=<?= $id ?>">
                    <button type="submit" class="btn btn--fav <?= $specialite ? 'btn--fav-active' : '' ?>">
                        <?= $specialite ? '👨‍🍳 Spécialitée' : '👨‍🍳 Ajouter aux spécialitées' ?>
                    </button>
                </form>

                <a href="/recettes/modifier?id=<?= $id ?>" class="btn btn--ghost">✏️ Modifier</a>
            </div>
        </div>
    </div>

    <!-- ── Corps : ingrédients + étapes ──────────────────────── -->
    <div class="detail-body">

        <!-- Ingrédients -->
        <?php if (!empty($ingredients)): ?>
        <aside class="detail-ingredients">
            <h2 class="detail-section-title">🧂 Ingrédients</h2>
            <ul class="ingredients-list">
                <?php foreach ($ingredients as $ing): ?>
                <li class="ingredient-item">
                    <span class="ingredient-name"><?= htmlspecialchars($ing['nom'] ?? '') ?></span>
                    <span class="ingredient-qty">
                        <?= htmlspecialchars($ing['quantite'] ?? '') ?>
                        <?= htmlspecialchars($ing['unite']    ?? '') ?>
                    </span>
                </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        <?php endif; ?>

        <!-- Étapes -->
        <?php if (!empty($etapes)): ?>
        <section class="detail-etapes">
            <h2 class="detail-section-title">📋 Préparation</h2>
            <ol class="etapes-list">
                <?php foreach ($etapes as $i => $etape): ?>
                <li class="etape-item">
                    <div class="etape-num"><?= $i + 1 ?></div>
                    <div class="etape-content">
                        <?php if (!empty($etape['titre'])): ?>
                            <h3 class="etape-title"><?= htmlspecialchars($etape['titre']) ?></h3>
                        <?php endif; ?>
                        <p class="etape-desc"><?= htmlspecialchars($etape['description'] ?? '') ?></p>
                    </div>
                </li>
                <?php endforeach; ?>
            </ol>

            <div style="margin-top:32px; text-align:center;">
                <a href="/recettes/<?= $id ?>/lancer" class="btn btn--launch btn--launch-lg">
                    ▶ Lancer la recette pas à pas
                </a>
            </div>
        </section>
        <?php endif; ?>

    </div><!-- /.detail-body -->

</div><!-- /.detail-page -->

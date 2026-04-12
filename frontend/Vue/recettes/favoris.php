<?php
/**
 * Vue/favoris.php — Page des recettes favorites
 *
 * Variables attendues du contrôleur :
 *   array $recettes  — liste des recettes favorites
 */

$total = count($recettes ?? []);
?>

<section class="page-hero">
    <h1 class="page-title">❤️ Mes <em>favoris</em></h1>
    <p class="page-subtitle">Tes recettes préférées, toujours à portée de main.</p>
</section>

<div class="favoris-page">

    <?php if (!empty($recettes)): ?>

    <div class="results-header" style="max-width:1280px; margin:0 auto; padding:0 28px 20px;">
        <p class="results-count"><?= $total ?> recette<?= $total > 1 ? 's' : '' ?> en favori</p>
    </div>

    <div class="recipes-grid" style="max-width:1280px; margin:0 auto; padding:0 28px 56px;">
        <?php foreach ($recettes as $r): ?>
        <article class="recipe-card">
            <div class="card-image-wrap">
                <?php if (!empty($r['image'])): ?>
                    <img src="<?= htmlspecialchars($r['image']) ?>"
                         alt="<?= htmlspecialchars($r['nom']) ?>"
                         class="card-image" loading="lazy">
                <?php else: ?>
                    <div class="card-image card-image--placeholder">🍽️</div>
                <?php endif; ?>

                <?php
                $catClass = match(strtolower($r['categorie'] ?? '')) {
                    'plat', 'plat principal'   => 'card-category--main',
                    'végétarien', 'vegetarien' => 'card-category--veg',
                    default => ''
                };
                ?>
                <span class="card-category <?= $catClass ?>">
                    <?= htmlspecialchars($r['categorie'] ?? 'Autre') ?>
                </span>

                <!-- Retirer des favoris -->
                <form method="POST" action="/recettes/favori" class="favorite-form">
                    <input type="hidden" name="recette_id" value="<?= (int)$r['id'] ?>">
                    <input type="hidden" name="action"     value="toggle">
                    <input type="hidden" name="redirect"   value="/favoris">
                    <button type="submit" class="btn-favorite btn-favorite--active"
                            title="Retirer des favoris">❤️</button>
                </form>
            </div>

            <div class="card-body">
                <h3 class="card-title">
                    <a href="/recettes/<?= (int)$r['id'] ?>" class="card-title-link">
                        <?= htmlspecialchars($r['nom']) ?>
                    </a>
                </h3>
                <div class="card-meta">
                    <?php if (!empty($r['duree'])): ?>
                        <span class="meta-item">⏱ <?= htmlspecialchars($r['duree']) ?> min</span>
                    <?php endif; ?>
                    <?php if (!empty($r['personnes'])): ?>
                        <span class="meta-item">👤 <?= (int)$r['personnes'] ?> pers.</span>
                    <?php endif; ?>
                </div>
                <div class="card-actions">
                    <a href="/recettes/<?= (int)$r['id'] ?>" class="card-btn card-btn--view">Voir</a>
                    <?php if (!empty($r['etapes'])): ?>
                    <a href="/recettes/<?= (int)$r['id'] ?>/lancer" class="card-btn card-btn--launch">▶ Lancer</a>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endforeach; ?>
    </div>

    <?php else: ?>
    <div class="empty-state" style="min-height:50vh;">
        <span class="empty-icon">🤍</span>
        <p class="empty-message">Tu n'as pas encore de recettes favorites.</p>
        <a href="/recettes" class="btn btn--primary">Parcourir les recettes</a>
    </div>
    <?php endif; ?>

</div>

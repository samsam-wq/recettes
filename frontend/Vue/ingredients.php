<?php
/**
 * Vue/recettes.php — Page principale : liste des recettes
 *
 * Variables attendues du contrôleur :
 *   array  $recettes  — liste des recettes à afficher
 *   int    $total     — nombre total de résultats
 */
use \frontend\Controleur\IngredientControleur;

$ingredientControleur = IngredientControleur::getInstance();

$ingredients =$ingredientControleur->tousLesIngredient();
if ($ingredients['status_code'] === 200){
    $ingredients = $ingredients['data'];
}else{
    $ingredients=[];
}

if (isset($_GET['erreur'])){
    $erreur = $_GET['erreur'];
}
?>

<!-- ── Héro ──────────────────────────────────────────────────── -->
<section class="page-hero">
    <h1 class="page-title">Nos <em>bonnes</em> recettes</h1>
    <p class="page-subtitle">Simple, savoureux, fait maison.</p>
</section>

<!-- ── Barre de contrôle ─────────────────────────────────────── -->
<section class="controls-bar">
    <div class="controls-inner">

        <a href="/ingredients/ajouter" class="btn btn--add">➕ Ajouter</a>

    </div>
</section>

<?php if (isset($erreur)): ?>
    <div class="form-errors">
            <p class="form-error-item">⚠️ <?= htmlspecialchars($erreur) ?></p>
    </div>
<?php endif; ?>

<!-- ── Layout filtres + grille ───────────────────────────────── -->
<div style="max-width: 1280px; margin: 0 auto; padding: 0 28px 56px;">
    <!-- GRILLE -->
    <section class="recipes-section">

        <?php if (!empty($ingredients)): ?>
        <div class="recipes-grid">
            <?php foreach ($ingredients as $u): ?>
            <article class="recipe-card">

                <div class="card-body">
                    <h3 class="card-title">
                        <?= htmlspecialchars($u['nom']) ?>
                    </h3>
                    <div class="card-actions">
                        <a href="./ingredients/modifier?id=<?= (int)$u['Id_Ingredient'] ?>" class="card-btn card-btn--edit">✏️</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon">🍽️</span>
            <p class="empty-message">Aucun ustensile trouvé.</p>
            <a href="/recettes" class="btn btn--primary">Voir toutes les recettes</a>
        </div>
        <?php endif; ?>

    </section>
</div>

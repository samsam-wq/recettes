<?php
/**
 * Vue/recettes.php — Page principale : liste des recettes
 *
 * Variables attendues du contrôleur :
 *   array  $recettes  — liste des recettes à afficher
 *   int    $total     — nombre total de résultats
 */
use \frontend\Controleur\RecetteControleur;

$recetteControleur = RecetteControleur::getInstance();

$categorie = htmlspecialchars($_GET['categorie'] ?? '');
$duree     = htmlspecialchars($_GET['duree']     ?? '');
$q         = htmlspecialchars($_GET['q']         ?? '');

$cats = [
    ''           => 'Toutes',
    'dessert'=> '🍰 Dessert',
    'plat'       => '🍽️ Plat principal',
    'vegetarien' => '🥗 Végétarien',
    'entree'     => '🥣 Entrée',
];

if (isset($_GET['id'])){
    $reponse = $recetteControleur->laRecette($_GET['id']);
    if ($reponse['status_code']==200) {
        $recette = $reponse['data'];

        $recetteControleur->ajouterRecette(
            $recette['nom'],
            $recette['duree'],
            $recette['categorie'],
            $recette['image'],
            $_SESSION['groupe']
        );
    }
}

$reponse = $recetteControleur->toutesLesRecettes();
if ($reponse['status_code']==200) {
    $recettes = $reponse['data'];
}

$total     = $total ?? count($recettes ?? []);

if (isset($_GET['erreur'])){
    $erreur = $_GET['erreur'];
}

function recetteFilterUrl(array $overrides = []): string {
    $params = array_merge($_GET, $overrides);
    $params = array_filter($params, fn($v) => $v !== '' && $v !== null);
    return '/recettes' . ($params ? '?' . http_build_query($params) : '');
}
?>

<!-- ── Héro ──────────────────────────────────────────────────── -->
<section class="page-hero">
    <h1 class="page-title">Nos <em>bonnes</em> recettes</h1>
    <p class="page-subtitle">Simple, savoureux, fait maison.</p>
</section>

<?php if (isset($erreur)): ?>
    <div class="form-errors">
            <p class="form-error-item">⚠️ <?= htmlspecialchars($erreur) ?></p>
    </div>
<?php endif; ?>

    <!-- GRILLE -->
    <section class="recipes-grid">

        <?php if (!empty($recettes)): ?>
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
                        'plat', 'plat principal' => 'card-category--main',
                        'végétarien', 'vegetarien' => 'card-category--veg',
                        default => ''
                    };
                    ?>
                    <span class="card-category <?= $catClass ?>">
                        <?= htmlspecialchars($r['categorie'] ?? 'Autre') ?>
                    </span>
                </div>

                <div class="card-body">
                    <h3 class="card-title">
                        <a href="./recettes/detail?id=<?= (int)$r['Id_recette'] ?>" class="card-title-link">
                            <?= htmlspecialchars($r['nom']) ?>
                        </a>
                    </h3>
                    <div class="card-meta">
                        <?php if (!empty($r['duree'])): ?>
                            <span class="meta-item">⏱ <?= htmlspecialchars($r['duree']) ?> min</span>
                        <?php endif; ?>  
                    </div>
                    <div class="card-actions">
                        <a href="./recettes/detail?id=<?= (int)$r['Id_recette'] ?>" class="card-btn card-btn--view">Voir</a>
                        <?php if ($r['groupe'] != $_SESSION['groupe']): ?>
                            <a href="./recettesPublic?id=<?= (int)$r['Id_recette'] ?>" class="card-btn card-btn--view">Ajouter</a>
                        <?php endif; ?> 
                    </div>
                </div>
            </article>
            <?php endforeach; ?>


        <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon">🍽️</span>
            <p class="empty-message">Aucune recette trouvée.</p>
            <a href="/recettes" class="btn btn--primary">Voir toutes les recettes</a>
        </div>
        <?php endif; ?>

    </section>


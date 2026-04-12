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
$favoris   = isset($_GET['favoris']);

$cats = [
    ''           => 'Toutes',
    'dessert'=> '🍰 Dessert',
    'plat'       => '🍽️ Plat principal',
    'vegetarien' => '🥗 Végétarien',
    'entree'     => '🥣 Entrée',
];

if (isset($categorie) || isset($duree) || isset($q )){
    $reponse = $recetteControleur->filtrerRecettes($categorie,$duree,$q,$favoris);

    if ($reponse['status_code']==200) {
        $recettes = $reponse['data'];
    }
}else{
    $reponse = $recetteControleur->toutesLesRecettesDuGroupe();

    if ($reponse['status_code']==200) {
        $recettes = $reponse['data'];
    }
}

$total     = $total ?? count($recettes ?? []);
//TODO mettre en favori
if (isset($_GET['idFavori'])){

}

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

<!-- ── Barre de contrôle ─────────────────────────────────────── -->
<section class="controls-bar">
    <div class="controls-inner">

        <form class="search-form" method="GET" action="/recettes">
            <?php if ($categorie): ?><input type="hidden" name="categorie" value="<?= $categorie ?>"><?php endif; ?>
            <?php if ($duree):     ?><input type="hidden" name="duree"     value="<?= $duree ?>"><?php endif; ?>
            <?php if ($favoris):   ?><input type="hidden" name="favoris"   value="1"><?php endif; ?>
            <div class="search-input-wrap">
                <span class="search-icon">🔍</span>
                <input type="search" name="q" class="search-input"
                       placeholder="Rechercher une recette…" value="<?= $q ?>">
            </div>
            <button type="submit" class="btn btn--primary">Rechercher</button>
        </form>

        <?php if ($total > 0): ?>
            <?php $idAleatoire = $recettes[random_int(0, $total - 1)]['Id_recette']; ?>
            <form method="GET" action="./recettes/detail">
                <input type="hidden" name="id" value="<?= $idAleatoire ?>">
                <button type="submit" class="btn btn--secondary">🎲 Recette aléatoire</button>
            </form>
        <?php endif; ?>

        <a href="/recettes/ajouter" class="btn btn--add">➕ Ajouter</a>

    </div>
</section>

<?php if (isset($erreur)): ?>
    <div class="form-errors">
            <p class="form-error-item">⚠️ <?= htmlspecialchars($erreur) ?></p>
    </div>
<?php endif; ?>

<!-- ── Layout filtres + grille ───────────────────────────────── -->
<div class="layout-with-filters">

    <!-- FILTRES -->
    <aside class="filters-panel">
        <form method="GET" action="/recettes" class="filters-form">
            <?php if ($q): ?><input type="hidden" name="q" value="<?= $q ?>"><?php endif; ?>

            <h2 class="filters-title">Filtres</h2>

            <fieldset class="filter-group">
                <legend class="filter-label">Catégorie</legend>
                <div class="filter-options">
                    <?php foreach ($cats as $val => $label):
                        $active = ($categorie === $val) ? 'filter-option--active' : ''; ?>
                    <label class="filter-option <?= $active ?>">
                        <input type="radio" name="categorie" value="<?= $val ?>"
                               <?= ($categorie === $val) ? 'checked' : '' ?>>
                        <span class="filter-pill"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="filter-group">
                <legend class="filter-label">Durée max.</legend>
                <div class="filter-options">
                    <?php foreach (['' => 'Toutes', '30' => '⏱ ≤ 30 min', '60' => '⏱ ≤ 1h'] as $val => $label):
                        $active = ($duree === $val) ? 'filter-option--active' : ''; ?>
                    <label class="filter-option <?= $active ?>">
                        <input type="radio" name="duree" value="<?= $val ?>"
                               <?= ($duree === $val) ? 'checked' : '' ?>>
                        <span class="filter-pill"><?= $label ?></span>
                    </label>
                    <?php endforeach; ?>
                </div>
            </fieldset>

            <fieldset class="filter-group">
                <legend class="filter-label">Options</legend>
                <div class="filter-options">
                    <label class="filter-option filter-option--toggle <?= $favoris ? 'filter-option--active' : '' ?>">
                        <input type="checkbox" name="favoris" value="1" <?= $favoris ? 'checked' : '' ?>>
                        <span class="filter-pill">❤️ Favoris seulement</span>
                    </label>
                </div>
            </fieldset>

            <button type="submit" class="btn btn--filter-apply">Appliquer</button>
            <a href="/recettes" class="btn--reset">Tout effacer</a>
        </form>
    </aside>

    <!-- GRILLE -->
    <section class="recipes-section">

        <div class="results-header">
            <p class="results-count"><?= $total ?> recette<?= $total > 1 ? 's' : '' ?></p>
            <?php if ($categorie || $duree || $favoris || $q): ?>
            <div class="active-filters">
                <?php if ($q): ?>
                    <span class="active-filter-tag">"<?= $q ?>"
                        <a href="<?= recetteFilterUrl(['q' => '']) ?>" class="tag-remove">×</a></span>
                <?php endif; ?>
                <?php if ($categorie): ?>
                    <span class="active-filter-tag"><?= $cats[$categorie] ?? $categorie ?>
                        <a href="<?= recetteFilterUrl(['categorie' => '']) ?>" class="tag-remove">×</a></span>
                <?php endif; ?>
                <?php if ($duree): ?>
                    <span class="active-filter-tag">≤ <?= $duree ?> min
                        <a href="<?= recetteFilterUrl(['duree' => '']) ?>" class="tag-remove">×</a></span>
                <?php endif; ?>
                <?php if ($favoris): ?>
                    <span class="active-filter-tag">Favoris
                        <a href="<?= recetteFilterUrl(['favoris' => '']) ?>" class="tag-remove">×</a></span>
                <?php endif; ?>
            </div>
            <?php endif; ?>
        </div>

        <?php if (!empty($recettes)): ?>
        <div class="recipes-grid">
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

                    <form method="POST" action="/recettes?idFavori=<?= (int)$r['Id_recette'] ?>" class="favorite-form">
                        <input type="hidden" name="recette_id" value="<?= (int)$r['Id_recette'] ?>">
                        <input type="hidden" name="action" value="toggle">
                        <input type="hidden" name="redirect" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
                        <button type="submit" class="btn-favorite <?= ( $r['notes']!==null && !empty($r['notes']['favori'])) ? 'btn-favorite--active' : '' ?>"
                                title="<?= ( $r['notes']!==null && !empty($r['notes']['favori'])) ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                            <?= ( $r['notes']!==null && !empty($r['notes']['favori'])) ? '❤️' : '🤍' ?>
                        </button>
                    </form>
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
                        <?php if (!empty($r['personnes'])): ?>
                            <span class="meta-item">👤 <?= (int)$r['personnes'] ?> pers.</span>
                        <?php endif; ?>
                    </div>
                    <div class="card-actions">
                        <a href="./recettes/detail?id=<?= (int)$r['Id_recette'] ?>" class="card-btn card-btn--view">Voir</a>
                        <a href="./recettes/modifier?id=<?= (int)$r['Id_recette'] ?>" class="card-btn card-btn--edit">✏️</a>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <?php else: ?>
        <div class="empty-state">
            <span class="empty-icon">🍽️</span>
            <p class="empty-message">Aucune recette trouvée.</p>
            <a href="/recettes" class="btn btn--primary">Voir toutes les recettes</a>
        </div>
        <?php endif; ?>

    </section>
</div>

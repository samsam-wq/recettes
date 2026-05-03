<?php
/**
 * Vue/recettes/modifier.php — Formulaire de modification d'une recette
 *
 * Variables attendues du contrôleur :
 *   array      $recette  — données actuelles de la recette
 *   array|null $erreurs  — messages d'erreur de validation
 *   array|null $old      — anciennes valeurs soumises (priorité sur $recette)
 */
use \frontend\Controleur\UstensileControleur;

$ustensileControleur = UstensileControleur::getInstance();

$erreurs = array();

if (!isset($_GET['id'])){
    header('Location: /ustensiles');
    exit();
}

if (isset($_POST['nom'])){
    $reponse = $ustensileControleur->modifierUstensile($_GET['id'],$_POST['nom']);
    if ($reponse['status_code']==200) {
        header('Location: /ustensiles');
        exit();
    }else{
        $erreurs[] = $reponse['status_message'];
    }
}

$reponse = $ustensileControleur->lUstensile($_GET['id']);
if ($reponse['status_code']==200) {
    $ustensile = $reponse['data'];
}else{
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Recette introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$erreurs = $erreurs ?? [];

$data = array_merge($ustensile, $old ?? []);

$id = (int)$ustensile['Id_Ustensiles'];

function modVal(array $data, string $key, string $default = ''): string {
    return htmlspecialchars($data[$key] ?? $default);
}
?>

<div class="form-page">

    <nav class="breadcrumb">
        <a href="/ustensiles" class="breadcrumb-link">← Touts les ustensiles</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Modifier</span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">✏️ Modifier l'ustensile</h1>
        <p class="form-page-subtitle"><?= htmlspecialchars($ustensile['nom'] ?? '') ?></p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST"
          action="/ustensiles/modifier?id=<?= $id ?>" enctype="multipart/form-data">

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

        <!-- ── Soumission ─────────────────────────────────────── -->
        <div class="form-submit-row">
            <a href="/recettes?id=<?= $id ?>" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="1" class="btn btn--launch">
                💾 Enregistrer les modifications
            </button>
        </div>

    </form>

        <!-- ── Zone danger ────────────────────────────────────── -->
        <form class="recipe-form" method="GET"
          action="./supprimer?id=<?= $id ?>" enctype="multipart/form-data">
            <div class="form-card form-card--danger">
                <button type="submit" name="delete" value="<?=(int) $id ?>"
                        class="btn btn--danger-solid" formnovalidate
                        onclick="return confirm('Supprimer définitivement cet ustensile ?')">
                    🗑️ Supprimer l'ustensile
                </button>
                <p>
                    La suppression est définitive et irréversible.
                </p>
            </div>
        </form>

</div>

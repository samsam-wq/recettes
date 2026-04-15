<?php
/**
 * Vue/recettes/modifier.php — Formulaire de modification d'une recette
 *
 * Variables attendues du contrôleur :
 *   array      $recette  — données actuelles de la recette
 *   array|null $erreurs  — messages d'erreur de validation
 *   array|null $old      — anciennes valeurs soumises (priorité sur $recette)
 */

if (isset($_POST['add_etape'])){
    header('Location: /recettes/ajouterEtape?id='.$_POST['id'].'&numero='.$_POST['add_etape']);
    exit();
}

use \frontend\Controleur\RecetteControleur;
use \frontend\Controleur\NoterControleur;
use \frontend\Controleur\EtapeControleur;

$recetteControleur = RecetteControleur::getInstance();
$noterControleur = NoterControleur::getInstance();
$etapeControleur = EtapeControleur::getInstance();

$erreurs = array();
$redirection = false;

//TODO supprimer les ingrédient et ustenssiles utilisés
if (isset($_POST['remove_etape'])){
    $reponse = $etapeControleur->supprimerEtape($_POST['id'],$_POST['remove_etape']);
    if ($reponse['status_code'] !== 200){
        $erreurs[] = $reponse['status_message'];
    }
}

if (
    isset($_POST['id']) && 
    isset($_POST['nom']) && 
    isset($_POST['duree']) && 
    isset($_POST['categorie']) &&
    isset($_POST['image']) 
){
    $reponse = $recetteControleur->modifierRecette(
        $_POST['id'],
        $_POST['nom'],
        $_POST['duree'],
        $_POST['categorie'],
        $_POST['image'],
        $_SESSION['groupe']
    );
    if ($reponse['status_code'] === 200){
        $redirection = true;
    }else{
        $erreurs[] = $reponse['status_message'];
        $old['nom'] = $_POST['nom'];
        $old['duree'] =$_POST['duree'];
        $old['categorie'] = $_POST['categorie'];
        $old['image'] = $_POST['image'];
    }
}

if (isset($_POST['id']) && isset($_POST['points'])) {
    $reponse = $noterControleur->modifierNoteNote($_POST['id'],$_POST['points']);
    if ($reponse['status_code'] === 200){
        $redirection = true;
    }else{
        $erreurs[] = $reponse['status_message'];
        $oldNote['points'] = $_POST['points'];
    }
}

$reponse = $recetteControleur->laRecette($_GET['id']);

if ($reponse['status_code']==200) {
    $recette = $reponse['data'];

    $etapes = $etapeControleur->lesEtapesDuPlat($_GET['id']);
    if ($etapes['status_code']===200) {
        $etapes = $etapes['data'];
    }else{
        $etapes = [];
    }

    for($i=0 ; $i<count($etapes) ; $i++){
        if (isset($_POST['etapes'][$i+1]['titre']) && isset($_POST['etapes'][$i+1]['contenu'])){
            $etape = $etapes[$i];
            $update['titre'] = $_POST['etapes'][$i+1]['titre'];
            $update['contenu'] = $_POST['etapes'][$i+1]['contenu'];
            if ($etape['titre'] !== $update['titre'] || $etape['contenu'] !== $update['contenu']) {
                $reponse = $etapeControleur->modifierEtape($update['titre'],$update['contenu'],($i+1),$_GET['id']);
                if ($reponse['status_code']!==200){
                    $erreurs[] = $reponse['status_message'];

                }
            }
        }
    }
}else{
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Recette introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

if ($redirection && empty($erreurs)) {
    header('Location: /recettes');
    exit();
}

$erreurs = $erreurs ?? [];
// $old prend la priorité sur $recette pour re-peupler après une erreur de validation
$data = array_merge($recette, $old ?? []);
$dataEtape = array_merge($etapes,$old ?? []);
if(isset($recette['notes'])){
    $dataNote = array_merge($recette['notes'], $oldNote ?? []);
}

$id          = (int)$recette['Id_recette'];
$ingredients = $data['ingredients'] ?? $recette['ingredients'] ?? [['nom' => '', 'quantite' => '', 'unite' => '']];
$etapes      = $dataEtapes['etapes']      ?? $etapes     ?? [['titre' => '', 'description' => '']];

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
          action="/recettes/modifier?id=<?= $id ?>" enctype="multipart/form-data">

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
                <label for="categorie">Catégorie</label>
                <select id="categorie" name="categorie">
                    <option value="">— Choisir —</option>
                    <?php foreach (['Dessert', 'Plat', 'Végétarien', 'Entree', 'Soupe', 'Autre'] as $c): ?>
                    <option value="<?= $c ?>" <?= strtolower(modVal($data, 'categorie')) === strtolower($c) ? 'selected' : '' ?>>
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

            <div class="row">
                <label for="duree">Note</label>
                <input type="number" id="points" name="points" min="1" max="5"
                       value="<?php echo isset($dataNote) ? modVal($dataNote, 'note') : 1 ?>">
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
                        <input type="text" name="etapes[<?= $idx+1 ?>][titre]"
                               placeholder="Titre de l'étape"
                               value="<?= htmlspecialchars($etape['titre'] ?? '') ?>">
                        <textarea name="etapes[<?= $idx+1 ?>][contenu]"
                                  placeholder="Description…"><?= htmlspecialchars($etape['contenu'] ?? '') ?></textarea>
                    </div>
                    <button type="submit" name="remove_etape" value="<?= $idx + 1 ?>"
                            class="btn-remove" formnovalidate title="Supprimer">✕</button>
                </div>
                <?php endforeach; ?>
                <?php 
                    if(empty($etapes)){
                        $idNouvelleEtape = $idx+1;
                    }else{
                        $idNouvelleEtape = $idx+2;
                    }
                ?>
            </div>

            <button type="submit" name="add_etape" value="<?=$idNouvelleEtape?>"
                    class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter une étape
            </button>
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
                        onclick="return confirm('Supprimer définitivement cette recette ?')">
                    🗑️ Supprimer la recette
                </button>
                <p>
                    La suppression est définitive et irréversible.
                </p>
            </div>
        </form>

</div>

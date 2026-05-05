<?php
use \frontend\Controleur\EtapeControleur;
use \frontend\Controleur\UstensileControleur;
use \frontend\Controleur\UtiliseControleur;

$utiliseControleur = UtiliseControleur::getInstance();
$ustensileControleur = UstensileControleur::getInstance();
$etapeControleur = EtapeControleur::getInstance();

$erreurs = array();

if (!isset($_GET['id']) || !isset($_GET['numero'])){
    header('Location: /recettes');
    exit();
}

if (isset($_POST['delete'])){
    $reponse = $utiliseControleur->supprimerUtiliseEtape($_GET['id'],$_GET['numero']);
    //TODO supprimer ingrédients
    $reponse = $etapeControleur->supprimerEtape($_GET['id'],$_GET['numero']);
    if ($reponse['status_code'] === 200){
        header('Location: /recettes/modifier?id='.$_GET['id']);
        exit();
    }else{
        $erreurs[] = $reponse['status_message'];
    }
}

if (isset($_POST['titre']) && isset($_POST['contenu']) && isset($_POST['save'])){
    $reponse = $etapeControleur->modifierEtape($_POST['titre'], $_POST['contenu'], $_GET['numero'], $_GET['id']);
    if ($reponse['status_code'] == 200) {
        header('Location: /recettes/modifier?id='.$_GET['id']);
        exit();
    }else{
        $erreurs[] = $reponse['status_message'];
        $old['contenu'] = $_POST['contenu'];
        $old['titre'] = $_POST['titre'];
    }
}

if (isset($_POST['add_ustensile_id']) && $_POST['add_ustensile_id'] != 0 && isset($_POST['add_ustensile'])){
    $reponse = $utiliseControleur->ajouterUtilise($_POST['add_ustensile_id'],$_GET['id'],$_GET['numero'],$_POST['quantite']);
    if ($reponse['status_code'] !== 201) {
        $erreurs[] = $reponse['status_message'];
    }
}

if (isset($_POST['del_all_ustensile'])){
    $reponse = $utiliseControleur->supprimerUtiliseEtape($_GET['id'],$_GET['numero']);
    if ($reponse['status_code'] !== 200) {
        $erreurs[] = $reponse['status_message'];
    }
}

if (isset($_POST['remove_ustensile'])){
    $reponse = $utiliseControleur->supprimerUtilise($_POST['remove_ustensile'],$_GET['id'],$_GET['numero']);
    if ($reponse['status_code'] !== 200) {
        $erreurs[] = $reponse['status_message'];
    }
}

$reponse = $etapeControleur->lEtape($_GET['id'], $_GET['numero']);
if ($reponse['status_code'] == 200) {
    $etape = $reponse['data'];

    $tousLesUstensiles = $ustensileControleur->tousLesUstensile();
    if ($tousLesUstensiles['status_code']===200) {
        $tousLesUstensiles = $tousLesUstensiles['data'];
    }else{
        $tousLesUstensiles = [];
    }

    $ustensiles = $ustensileControleur->tousLesUstensileDeEtape($_GET['id'], $_GET['numero']);
    if ($ustensiles['status_code']===200) {
        $ustensiles = $ustensiles['data'];
    }else{
        $ustensiles = [];
    }
}else{
    echo '<div class="empty-state"><span class="empty-icon">😕</span>
          <p class="empty-message">Étape introuvable.</p>
          <a href="/recettes" class="btn btn--primary">Retour aux recettes</a></div>';
    return;
}

$erreurs = $erreurs ?? [];
$data = array_merge($etape, $old ?? []);

function modVal(array $data, string $key, string $default = ''): string {
    return htmlspecialchars($data[$key] ?? $default);
}
?>

<div class="form-page">

    <nav class="breadcrumb">
        <a href="/recettes" class="breadcrumb-link">← Toutes les recettes</a>
        <span class="breadcrumb-sep">/</span>
        <a href="/recettes/modifier?id=<?= $_GET['id'] ?>" class="breadcrumb-link">Modifier la recette</a>
        <span class="breadcrumb-sep">/</span>
        <span class="breadcrumb-current">Modifier l'étape <?= $_GET['numero'] ?></span>
    </nav>

    <div class="form-page-header">
        <h1 class="form-page-title">✏️ Modifier l'étape</h1>
        <p class="form-page-subtitle">Étape <?= $_GET['numero'] ?></p>
    </div>

    <?php if (!empty($erreurs)): ?>
    <div class="form-errors">
        <?php foreach ($erreurs as $e): ?>
            <p class="form-error-item">⚠️ <?= htmlspecialchars($e) ?></p>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <form class="recipe-form" method="POST"
          action="/recettes/modifierEtape?id=<?= $_GET['id'] ?>&numero=<?= $_GET['numero'] ?>">

        <div class="form-card">
            <h2 class="form-card-title">📋 Étape <?= $_GET['numero'] ?></h2>

            <div class="dynamic-row dynamic-row--etape">
                <span class="etape-num-badge"><?= $_GET['numero'] ?></span>
                <div class="dynamic-row-inputs dynamic-row-inputs--etape">
                    <input type="text" name="titre"
                           placeholder="Titre de l'étape"
                           value="<?= modVal($data, 'titre') ?>">
                    <textarea name="contenu"
                              placeholder="Description détaillée de cette étape…"><?= modVal($data, 'contenu') ?></textarea>
                </div>
            </div>
        </div>

        <div class="form-card">
            <h2 class="form-card-title">🍴 Ustensiles</h2>
            <?php if ($ustensiles) : ?>
                <?php foreach($ustensiles as $ustensile) : ?>
                    <ul>
                        <div style="display: flex">
                            <li><?= htmlspecialchars($ustensile['nom']) ?> x <?= htmlspecialchars($ustensile['quantite']) ?> </li>
                            <button type="submit" name="remove_ustensile" value="<?= htmlspecialchars($ustensile['Id_Ustensiles']) ?>"
                            class="btn-remove-inline" formnovalidate title="Supprimer">✕</button>
                        </div>
                    </ul>
                <?php endforeach;?>
            <?php endif; ?>
            <div style="display: flex">
                <select name="add_ustensile_id">
                    <option value ="0">Pas d'ustensile</option>
                    <?php 
                        foreach($tousLesUstensiles as $ustensile){
                            echo '<option value ="'.$ustensile['Id_Ustensiles'].'">'. $ustensile['nom'] .'</option>';
                        }
                    ?>
                </select>
                <p style="margin : 5px"> x </p>
                <input type="number" name="quantite" min="1" step="1" 
                    placeholder="1" value="1">
            </div>
            <button type="submit" name="add_ustensile" value=""
                class="btn btn--ghost btn--add-row" formnovalidate>
                ＋ Ajouter un ustensile</button>
            <?php if (!empty($ustensiles)) : ?>
            <button type="submit" name="del_all_ustensile" value=""
                class="btn btn--ghost btn--add-row" formnovalidate>
                Supprimer tous les ustensiles</button>
            <?php endif; ?>
        </div>  

        <div class="form-submit-row">
            <a href="/recettes/modifier?id=<?= $_GET['id'] ?>" class="btn btn--ghost">Annuler</a>
            <button type="submit" name="save" value="1" class="btn btn--launch">
                💾 Enregistrer les modifications
            </button>
        </div>

        <div class="form-card form-card--danger">
            <button type="submit" name="delete" class="btn btn--danger-solid" formnovalidate
                    onclick="return confirm('Supprimer définitivement cette étape ?')">
                🗑️ Supprimer l'étape
            </button>
            <p>La suppression est définitive et irréversible.</p>
        </div>
    </form>

</div>
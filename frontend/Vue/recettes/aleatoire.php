<?php
/**
 * Vue/recettes/aleatoire.php — Recette aléatoire
 *
 * Le contrôleur doit peupler $recetteId avec l'id d'une recette aléatoire,
 * ou faire lui-même le header Location. Ce fichier gère les deux cas.
 *
 * Variables attendues du contrôleur :
 *   int|null $recetteId  — id de la recette choisie aléatoirement
 */

// Si le contrôleur a déjà fait la redirection, on n'arrive jamais ici.
// Sinon on redirige depuis la vue.
if (!empty($recetteId)) {
    header('Location: /recettes/' . (int)$recetteId);
    exit();
}
?>

<div class="empty-state" style="min-height:60vh;">
    <span class="empty-icon">🎲</span>
    <p class="empty-message">Aucune recette disponible pour le tirage aléatoire.</p>
    <a href="/recettes" class="btn btn--primary">Voir toutes les recettes</a>
</div>

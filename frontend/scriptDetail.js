function majIngredients(multiplicateur) {
    document.querySelectorAll('.ingredient-qty').forEach(function(el) {
        var base = parseFloat(el.dataset.base);
        var valeur = base * multiplicateur;
        // Affiche sans décimale si entier, sinon 1 décimale
        el.textContent = (valeur % 1 === 0 ? valeur : valeur.toFixed(1))
                         + ' ' + el.dataset.unite;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var base = parseFloat(document.getElementById('personneBase').textContent) || 2;
    majIngredients(base);

    var input = document.getElementById('personnes');
    var btnMoins = input.previousElementSibling;
    var btnPlus  = input.nextElementSibling;

    input.value=base;

    btnMoins.addEventListener('click', function() {
        if (input.value > input.min) {
            input.value--;
            majIngredients(input.value);
        }
    });

    btnPlus.addEventListener('click', function() {
        if (input.value < input.max) {
            input.value++;
            majIngredients(input.value);
        }
    });
});



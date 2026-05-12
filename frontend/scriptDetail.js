function majIngredients(multiplicateur, baseP) {
    document.querySelectorAll('.ingredient-qty').forEach(function(el) {
        var base = parseFloat(el.dataset.base);
        var valeur = base * (multiplicateur / baseP);
        el.textContent = (valeur % 1 === 0 ? valeur : valeur.toFixed(1))
                         + ' ' + el.dataset.unite;
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var baseP = parseFloat(document.getElementById('personneBase').textContent) || 2;
    majIngredients(baseP, baseP);

    var input = document.getElementById('personnes');
    input.value = baseP;

    var btnMoins = input.previousElementSibling;
    var btnPlus  = input.nextElementSibling;

    btnMoins.addEventListener('click', function() {
        if (input.value > input.min) {
            input.value--;
            majIngredients(input.value, baseP);
        }
    });

    btnPlus.addEventListener('click', function() {
        if (input.value < input.max) {
            input.value++;
            majIngredients(input.value, baseP);
        }
    });
});
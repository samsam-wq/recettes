<?php

namespace frontend\Vue\Component;

/**
 * Composant Formulaire
 * Génère des formulaires HTML avec le design Nos Recettes.
 * API identique à l'originale — aucune rupture de compatibilité.
 */
class Formulaire {

    private string $formulaire;

    public function __construct(string $returnName) {
        $this->formulaire = '<div class="container"><form action="' . htmlspecialchars($returnName) . '" method="POST">';
    }

    /** Champ texte */
    public function setText(string $description, string $name, string $placeholder = '', string $value = ''): void {
        $this->formulaire .= '
        <div class="row">
            <label for="field-' . htmlspecialchars($name) . '">' . htmlspecialchars($description) . '</label>
            <input type="text"
                id="field-' . htmlspecialchars($name) . '"
                name="' . htmlspecialchars($name) . '"
                placeholder="' . htmlspecialchars($placeholder) . '"
                value="' . htmlspecialchars($value) . '"
            >
        </div>';
    }

    /** Champ date */
    public function setDate(string $description, string $name, string $value = ''): void {
        $this->formulaire .= '
        <div class="row">
            <label for="field-' . htmlspecialchars($name) . '">' . htmlspecialchars($description) . '</label>
            <input type="date"
                id="field-' . htmlspecialchars($name) . '"
                name="' . htmlspecialchars($name) . '"
                value="' . htmlspecialchars($value) . '"
            >
        </div>';
    }

    /** Champ datetime-local */
    public function setDateTime(string $description, string $name, ?string $min = null, string $value = ''): void {
        $minAttr = $min ? ' min="' . htmlspecialchars($min) . '"' : '';
        $this->formulaire .= '
        <div class="row">
            <label for="field-' . htmlspecialchars($name) . '">' . htmlspecialchars($description) . '</label>
            <input type="datetime-local"
                id="field-' . htmlspecialchars($name) . '"
                name="' . htmlspecialchars($name) . '"
                value="' . htmlspecialchars($value) . '"' . $minAttr . '
            >
        </div>';
    }

    /** Champ select */
    public function setSelect(string $description, array $values, string $name, string $selectedValue = ''): void {
        $options = '';
        foreach ($values as $v) {
            $sel      = ($v == $selectedValue) ? ' selected' : '';
            $options .= '<option value="' . htmlspecialchars($v) . '"' . $sel . '>' . htmlspecialchars($v) . '</option>';
        }
        $this->formulaire .= '
        <div class="row">
            <label for="field-' . htmlspecialchars($name) . '">' . htmlspecialchars($description) . '</label>
            <select id="field-' . htmlspecialchars($name) . '" name="' . htmlspecialchars($name) . '">
                ' . $options . '
            </select>
        </div>';
    }

    /** Champ textarea */
    public function addTextArea(string $name, ?string $description = null, string $value = ''): void {
        $labelHtml = $description
            ? '<label for="field-' . htmlspecialchars($name) . '">' . htmlspecialchars($description) . '</label>'
            : '';
        $this->formulaire .= '
        <div class="row' . ($description ? '' : ' row--no-label') . '">
            ' . $labelHtml . '
            <textarea id="field-' . htmlspecialchars($name) . '" name="' . htmlspecialchars($name) . '">'
                . htmlspecialchars($value) .
            '</textarea>
        </div>';
    }

    /** Champ caché */
    public function addHiddenInput(string $name, string $value): void {
        $this->formulaire .= '<input type="hidden" name="' . htmlspecialchars($name) . '" value="' . htmlspecialchars($value) . '">';
    }

    /**
     * Ajoute un bouton de soumission.
     *
     * @param string $type   'submit' | 'button' | 'reset'
     * @param string $class  '' (primary), 'create', 'update', 'delete', 'largeSubmit'
     * @param string $name   Attribut name
     * @param string $value  Label du bouton
     */
    public function addButton(string $type, string $class, string $name = '', string $value = ''): void {
        $this->formulaire .= '
        <div class="row row--action" style="margin-top:8px;">
            <input type="' . htmlspecialchars($type) . '"
                class="' . htmlspecialchars($class) . '"
                name="' . htmlspecialchars($name) . '"
                value="' . htmlspecialchars($value) . '"
            >
        </div>';
    }

    public function __toString(): string {
        return $this->formulaire . '</form></div>';
    }
}

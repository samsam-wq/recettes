<?php

namespace frontend\Vue\Component;

/**
 * Composant Select
 * Génère un <select> stylisé avec le design Nos Recettes.
 * API identique à l'originale — aucune rupture de compatibilité.
 */
class Select {

    private array   $values;
    private string  $name;
    private ?string $description;
    private ?string $selectedValue;

    public function __construct(
        array   $values,
        string  $name,
        ?string $description   = null,
        ?string $selectedValue = null
    ) {
        $this->values        = $values;
        $this->name          = $name;
        $this->description   = $description;
        $this->selectedValue = $selectedValue;
    }

    public function toHTML(): void { ?>
        <div class="row">

            <?php if ($this->description !== null): ?>
            <label for="select-<?= htmlspecialchars($this->name) ?>">
                <?= htmlspecialchars($this->description) ?>
            </label>
            <?php endif; ?>

            <div <?= $this->description === null ? 'style="grid-column:1/-1;"' : '' ?>>
                <select
                    id="select-<?= htmlspecialchars($this->name) ?>"
                    name="<?= htmlspecialchars($this->name) ?>"
                >
                    <?php if ($this->selectedValue === null): ?>
                        <option value="" selected></option>
                    <?php endif; ?>

                    <?php foreach ($this->values as $key => $value): ?>
                        <option
                            value="<?= htmlspecialchars((string) $key) ?>"
                            <?= ($this->selectedValue == $value) ? 'selected' : '' ?>
                        ><?= htmlspecialchars((string) $value) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

        </div>
    <?php }
}

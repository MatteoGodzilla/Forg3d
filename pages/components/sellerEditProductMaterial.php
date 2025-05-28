<?php function generateEditVariant($variant, int $defaultVariant){ ?>
<div class="variantInfo">
    <input type="hidden" name="materialIds[]" value="<?= $variant["id"] ?>" multiple/>
    <div>
        <svg width="40" height="40px">
            <ellipse stroke="black" fill="#<?= $variant["hexColore"]?>" stroke-width="2" rx="16" ry="16" cx="20" cy="20"></ellipse>
        </svg>
        <label><?= $variant["nomeColore"]?> (<?= $variant["tipologia"]?>)</label>
    </div>
    <div>
        <label for="variantCosts[<?= $variant["id"] ?>]">Centesimi:</label>
        <input type="number" name="variantCosts[<?= $variant["id"] ?>]" id="variantCosts[<?= $variant["id"] ?>]" value="<?= $variant["prezzo"] ?>" multiple/>
    </div>
    <div>
        <div>
            <input type="radio" name="defaultVariant" id="<?= $variant["id"] ?>" value="<?= $variant["id"] ?>" <?= ( $defaultVariant === $variant["id"] ) ? "checked" : ""?> />
            <label for="<?= $variant["id"] ?>">Default</label>
        </div>
        <div>
            <input type="checkbox" name="removeVariant[<?= $variant["id"]?>]" id="removeVariant[<?= $variant["id"]?>]"value="<?= $variant["id"] ?>" multiple/>
            <label for="removeVariant[<?= $variant["id"]?>]">Rimuovi</label>
        </div>
    </div>
</div>
<?php } ?>


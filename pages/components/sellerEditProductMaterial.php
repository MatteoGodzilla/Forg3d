<?php function generateEditVariant($variant, int $defaultVariant){ ?>
<div class="variantInfo">
    <input type="hidden" name="materialIds[<?= $variant["id"] ?>]" value="<?= $variant["id"] ?>"/>
    <div>
        <svg width="40" height="40px">
            <ellipse stroke="black" fill="#<?= $variant["hexColore"]?>" stroke-width="2" rx="16" ry="16" cx="20" cy="20"></ellipse>
        </svg>
        <label><?= htmlspecialchars($variant["nomeColore"])?> (<?= htmlspecialchars($variant["tipologia"]) ?>)</label>
    </div>
    <div>
        <input type="number" name="variantCostsWhole[<?= $variant["id"] ?>]" id="variantCostsWhole[<?= $variant["id"] ?>]" value="<?= floor($variant["prezzo"] / 100) ?>"/>
        <p>,</p>
        <input type="number" name="variantCostsCents[<?= $variant["id"] ?>]" id="variantCostsCents[<?= $variant["id"] ?>]" value="<?= $variant["prezzo"] % 100 ?>" min=0 max=99 step=1 />
        <label for="variantCostsWhole[<?= $variant["id"] ?>]">€</label>
    </div>
    <div>
        <div>
            <input type="radio" name="defaultVariant" id="<?= $variant["id"] ?>" value="<?= $variant["id"] ?>" <?= ( $defaultVariant === $variant["id"] ) ? "checked" : ""?> />
            <label for="<?= $variant["id"] ?>">Default</label>
        </div>
        <div>
            <input type="checkbox" name="removeVariant[<?= $variant["id"]?>]" id="removeVariant[<?= $variant["id"]?>]"value="<?= $variant["id"] ?>"/>
            <label for="removeVariant[<?= $variant["id"]?>]">Rimuovi</label>
        </div>
    </div>
</div>
<?php } ?>


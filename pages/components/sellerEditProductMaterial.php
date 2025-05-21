<?php function generateEditVariant($variant){ ?>
    <input type="hidden" name="materialIds[]" value="<?= $variant["id"] ?>" multiple/>
    <input type="radio" name="defaultVariant" id="<?= $variant["id"] ?>" value="<?= $variant["id"] ?>" 
        <?= (isset($product["varianteDefault"]) && $product["varianteDefault"] === $variant["id"] ) ? "checked" : ""?> />
    <label for="<?= $variant["id"] ?>">Default</label>
    <label><?= $variant["nomeColore"]?> (<?= $variant["tipologia"]?>)</label>

    <label><?= $variant["hexColore"]?></label>
    <input type=number" name="variantCosts[]" value="<?= $variant["prezzo"] ?>" multiple/>
    <label for="removeVariant[<?= $variant["id"]?>]">Rimuovi</label>
    <input type="checkbox" name="removeVariant[<?= $variant["id"]?>]" id="removeVariant[<?= $variant["id"]?>]"value="<?= $variant["id"] ?>" multiple/>
<?php } ?>


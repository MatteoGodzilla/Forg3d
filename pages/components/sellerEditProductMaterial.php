<?php function generateEditVariant($variant){ ?>
    <div class="variantInfo">
    <input type="hidden" name="materialIds[]" value="<?= $variant["id"] ?>" multiple/>
    <input type="radio" name="defaultVariant" id="<?= $variant["id"] ?>" value="<?= $variant["id"] ?>" 
        <?= (isset($product["varianteDefault"]) && $product["varianteDefault"] === $variant["id"] ) ? "checked" : ""?> />
    <label for="<?= $variant["id"] ?>">Default</label>
    <label><?= $variant["nomeColore"]?> (<?= $variant["tipologia"]?>)</label>

    <label><?= $variant["hexColore"]?></label>
    <input type="number" name="variantCosts[]" value="<?= $variant["prezzo"] ?>" multiple/>
    <label for="removeVariant[<?= $variant["id"]?>]">Rimuovi</label>
    <input type="checkbox" name="removeVariant[<?= $variant["id"]?>]" id="removeVariant[<?= $variant["id"]?>]"value="<?= $variant["id"] ?>" multiple/>
    <svg width="40" height="40px"><ellipse stroke="black" fill="#<?= $variant["hexColore"]?>" stroke-width="2" rx="18" ry="18" cx="18" cy="18"></ellipse></svg>
    </div>
<?php } ?>


<?php function generateProductVariant($variant):void { ?>
<div>
    <p><?php echo htmlspecialchars($variant["nomeColore"]); ?> (<?php echo htmlspecialchars($variant["tipologia"]); ?>)</p>
    <p>€<?php echo htmlspecialchars(number_format($variant['prezzo'] / 100, 2, ',', '.')); ?> </p>
    <p><?php echo htmlspecialchars($variant["hexColore"]);?></p>
    <a href="#">Modifica</a>
    <a href="#">Elimina</a>
</div>
<?php } ?>
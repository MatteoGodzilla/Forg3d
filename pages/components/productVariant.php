<?php function generateProductVariant($variant):void { ?>
<div>
    <p><?php echo($variant["nomeColore"]); ?> (<?php echo($variant["tipologia"]); ?>)</p>
    <p>€<?php echo(number_format($variant['prezzo'] / 100, 2, ',', '.')); ?> </p>
    <p><?php echo($variant["hexColore"]);?></p>
    <a href="#">Modifica</a>
    <a href="#">Elimina</a>
</div>
<?php } ?>
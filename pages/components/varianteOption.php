<?php function varianteOption($variante){ ?>
        <div class="variantOption">
            <input name="choice" type="radio" id ="variant<?=$variante["id"]?>"/> 
            <span><?php echo $variante['nomeColore']." ( ".$variante["prezzo"]."$ )"; ?></span>
            <small><?php echo $variante['tipologia']; ?></small>
            
        </div>
<?php }
        /*
            <p><strong>Materiale:</strong> <?php echo ($variante['tipologia']."(".$variante['nomeColore'].")"); ?></p>
            <p><strong>Colore:</strong> <?php echo $variante['nomeColore']; ?> (#<?php echo $variante['hexColore']; ?>)</p>
            <p><strong>Prezzo:</strong> €<?php echo number_format($variante['prezzo'] / 100, 2, ',', '.'); ?></p>
        */
?>

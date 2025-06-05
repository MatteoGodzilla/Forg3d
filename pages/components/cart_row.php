<?php
    function cart_row($row){
        $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
?>
    <div class="cart-row">
        <div>
            <a href="product.php?id=<?=$row["idProdotto"]?>">
                    <?php if(isset($row["immagine"]) && $row["immagine"]!=null && file_exists($rootDir.$row["immagine"])) {?>
                        <img  src="<?= $row["immagine"]?>"> </img>
                    <?php }else{?>
                        <img  alt = "immagine non trovata" src="https://placehold.co/800x600"> </img>
                    <?php }?>
            </a>
        </div>
        <div>
            <input name="ids[]" type="hidden" value=<?=$row["idVariante"]?>>
            <input name="rows[]" type="hidden" value=<?=$row["id"]?>>
            <input name="costs[]" type="hidden" value=<?=$row["prezzo"]?>>
            <h3><?=$row["nome"]?></h3>
            <p>Variante:<?=$row["variante"]?></p>
            <p>Quantità:</p> <input name="quantity[]" type="number" min="1" value=<?=$row["quantita"]?>>
            <h3 name="total[]"><?=$row["quantita"]*$row["prezzo"]?>$</h3>
            <a class="button-delete" href="./../api/removeFromCart.php?id=<?=$row["id"]?>">Rimuovi</a>
        </div>
    </div>

<?php 
    }
?>



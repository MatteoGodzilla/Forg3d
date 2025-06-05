<?php
    function cart_row($row){
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
            <h3><?=$row["nome"]?></h3>
            <p>Variante:<?=$row["variante"]?></p>
            <p>Quantità:</p> <input type="number" value=<?=$row["quantita"]?>>
        </div>
    </div>
    <div class="subtotal">
            <h3>Subtotale:</h3>
            <a class="button-delete" href="./../api/removeFromCart.php?id=<?=$row["id"]?>">Rimuovi</a>
    </div>
<?php 
    }
?>


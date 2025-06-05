<?php
    function cart_row($row){
?>
    <div class="cart-row">
        <a href="product.php?id=<?=$row["idProdotto"]?>">
            <div>
                <?php if(isset($row["immagine"]) && $row["immagine"]!=null && file_exists($rootDir.$row["immagine"])) {?>
                    <img  src="<?= $row["immagine"]?>"> </img>
                <?php }else{?>
                    <img  alt = "immagine non trovata" src="https://placehold.co/800x600"> </img>
                <?php }?>
            </div>
        </a>
        <div>
            <h3><?=$row["nome"]?></h3>
            <p>Variante:<?=$row["variante"]?></p>
            <p>Quantità:</p> <input type="number" value=<?=$row["quantita"]?>>
            <p>Subtotale:</p>
            <a href="./../api/removeFromCart.php?id=<?=$row["id"]?>">Rimuovi</a>
        </div>
    </div>
<?php 
    }
?>


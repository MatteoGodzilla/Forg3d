<?php
    function cart_row($row){
?>
    <div class="cart-row">
        <h3><?=$row["nome"]?></h3>
        <p>Variante:<?=$row["variante"]?></p>
        <p>Quantità:</p> <input type="number" value=<?=$row["quantita"]?>>
        <p>Subtotale:</p>
        <a href="./../api/removeFromCart.php?id=<?=$row["id"]?>">Rimuovi</a>
    </div>
<?php 
    }
?>
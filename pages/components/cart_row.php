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

<?php
function generateProductList($product) {
    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]);
    ?>
<article class="product">
    <a href="../product.php?id=<?php echo $product['id']; ?>">
        <div>
            <?php if(isset($product["immagine"]) && $product["immagine"]!=null && file_exists($rootDir.$product["immagine"])) {?>
                <img  src="<?= $product["immagine"]?>"> </img>
            <?php }else{?>
                <img  alt = "immagine non trovata" src="https://placehold.co/800x600"> </img>
            <?php }?>
        <h3><?php  echo $product['nome'] ?> </h3>
        <?php echo '<p>Venditore: ' . $product['venditoreNome'] . ' ' . $product['venditoreCognome'] . '</p>'?>
        </div>
    </a>
</article>
<?php
}
?>
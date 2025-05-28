
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

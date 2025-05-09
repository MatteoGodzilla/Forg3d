
<?php
function generateProductList($product) {?>
<article class="product">
    <div>
    <img src="https://placehold.co/800x600" alt="" />
    <h3><?php  echo $product['nome'] ?> </h3>
    <?php echo '<p>Venditore: ' . $product['venditoreNome'] . ' ' . $product['venditoreCognome'] . '</p>'?>
        <?php echo '<a href="../product.php?id=' . $product['id'] . '">Dettagli</a>'?>
    </div>
</article>
<?php
}
?>

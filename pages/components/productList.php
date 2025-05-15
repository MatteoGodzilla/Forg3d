
<?php
function generateProductList($product) {?>
<article class="product">
    <a href="../product.php?id=<?php echo $product['id']; ?>">
        <div>
        <img src="https://placehold.co/800x600" alt="" />
        <h3><?php  echo $product['nome'] ?> </h3>
        <?php echo '<p>Venditore: ' . $product['venditoreNome'] . ' ' . $product['venditoreCognome'] . '</p>'?>
        </div>
    </a>
</article>
<?php
}
?>

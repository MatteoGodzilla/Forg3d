
<?php
function generateProductList($product) {
    echo '<div class="product-card">';
    echo '<h3>' . $product['nome'] . '</h3>';
    echo '<p>Venditore: ' . $product['venditoreNome'] . ' ' . $product['venditoreCognome'] . '</p>';
    echo '<a href="../product.php?id=' . $product['id'] . '">Dettagli</a>';
    echo '</div>';
}
?>

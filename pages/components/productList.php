<?php function generateProductList($product) { 
    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]); 
?>
    <article class="product">
    <a href="../product.php?id=<?php echo $product['id']; ?>">
        <div>
            <?php if(isset($product["immagine"]) && $product["immagine"]!=null && file_exists($rootDir.$product["immagine"])) {?>
                <img alt="immagine prodotto" src="<?= $product["immagine"]?>"></img>
            <?php } else { ?>
                <img alt="immagine non trovata" src="https://placehold.co/800x600?text=Immagine+non+trovata"></img>
            <?php } ?>
        <h3><?php echo htmlspecialchars($product['nome']) ?></h3>
        <?php echo '<p>Venditore: ' . htmlspecialchars($product['venditoreNome']) . ' ' . htmlspecialchars($product['venditoreCognome']) . '</p>'?>
        </div>
    </a>
</article>
<?php } ?>

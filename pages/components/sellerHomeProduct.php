<?php function sellerHomeProduct($product){ 
    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]); 
?>
<article class="product">
    <!--TODO: add image in list -->
    <?php if(isset($product["immagine"]) && $product["immagine"] != null && file_exists($rootDir.$product["immagine"])){ ?>
        <img src="<?= $product["immagine"] ?>" alt="immagine prodotto" />
    <?php } else { ?>
        <img src="https://placehold.co/800x600?text=Immagine+non+trovata" alt="immagine non trovata" />
    <?php } ?>
    <div>
        <p><?php echo ($product['nome']); ?> <?php echo ($product['visibile'] == 2 ? "":"(Nascosto)") ?></p>
        <div>
            <a  class ="button-create" href="editProduct.php?id=<?php echo ($product['id']); ?>">Modifica</a>
            <a class ="button-delete" href="./api/deleteProduct.php?id=<?php echo ($product['id']); ?>">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>

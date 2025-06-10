<?php function sellerHomeProduct($product){ 
    $rootDir = realpath($_SERVER["DOCUMENT_ROOT"]); 
?>
<article class="product">
    <!--TODO: add image in list -->
    <?php if(isset($product["immagine"]) && $product["immagine"] != null && file_exists($rootDir.$product["immagine"])){ ?>
        <img alt="immagine prodotto" src="<?= $product["immagine"] ?>"  />
    <?php } else { ?>
        <img alt="immagine non trovata" src="https://placehold.co/800x600?text=Immagine+non+trovata"  />
    <?php } ?>
    <div>
        <h3><?php echo ($product['nome']); ?> <?php echo ($product['visibile'] == 2 ? "":"(Nascosto)") ?></h3>
        <div>
            <a  class ="button-create" href="editProduct.php?id=<?php echo ($product['id']); ?>">Modifica</a>
            <a class ="button-delete" href="./api/deleteProduct.php?id=<?php echo ($product['id']); ?>">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>

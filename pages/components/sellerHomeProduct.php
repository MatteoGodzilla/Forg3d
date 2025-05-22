<?php function sellerHomeProduct($product){ ?>
<article class="product">
    <!--TODO: add image in list -->
    <img src="https://placehold.co/800x600" alt="" />
    <div>
        <p><?php echo ($product['nome']); ?> <?php echo ($product['visibile'] == 2 ? "":"(Nascosto)") ?></p>
        <div>
            <a  class ="button-create" href="editProduct.php?id=<?php echo ($product['id']); ?>">Modifica</a>
            <a class ="button-delete" href="./api/deleteProduct.php?id=<?php echo ($product['id']); ?>">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>

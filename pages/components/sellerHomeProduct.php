<?php function sellerHomeProduct($product){ ?>
<article class="product">
    <!--TODO: add image in list -->
    <img src="https://placehold.co/800x600" alt="" />
    <div>
        <p><?php echo ($product['nome']); ?> <?php echo (($product['visibile']) ? "":"(Nascosto)") ?></p>
        <div>
            <a href="#">Modifica</a>
            <a href="#">Elimina</a>
        </div>
    </div>
</article>
<?php } ?>

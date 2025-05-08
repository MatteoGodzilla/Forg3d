<?php function productReport($product){ ?>
<article class="product">
    <div>
        <small><?php echo ($product['emailVenditore']); ?></small><br>
        <small><?php echo ($product['totalReports']); ?> segnalazioni</small><br>
        <p><?php echo ($product['nome'])?></p>
    </div>
</article>
<?php } ?>
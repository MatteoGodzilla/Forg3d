<?php function productReport($product){ ?>
<article class="product">
    <div>
        <small><?php echo ($product['emailVenditore']); ?></small><br>
        <p><?php echo ($product['nome'])?></p>
        <div>
            <a href="<?= "/checkProductReportDetail.php?id=".$product["idProdotto"]?>">Vai A segnalazioni <?="(".$product["totalReports"].")" ?></a>
    </div>
    </div>
</article>
<?php } ?>
<?php function productReport($product){ ?>

    <div class="report">
        <small><?php echo ($product['emailVenditore']); ?></small><br>
        <p><?php echo ($product['nome'])?></p>
        <a href="<?= "/checkProductReportDetail.php?id=".$product["idProdotto"]?>">Vai A segnalazioni <?="(".$product["totalReports"].")" ?></a>
    </div>
<?php } ?>
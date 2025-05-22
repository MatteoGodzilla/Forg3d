<?php function productReport($product){ ?>
    <div class="report">
        <p><?php echo ($product['nome'])?></p>
        <small><?php echo ($product['emailVenditore']); ?></small><br>
        <a class="detail" href="<?= "/checkProductReportDetail.php?id=".$product["idProdotto"]?>">Vai A segnalazioni <?="(".$product["totalReports"].")" ?></a>
    </div>
<?php } ?>
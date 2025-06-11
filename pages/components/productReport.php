<?php function productReport($product){ ?>
    <div class="report">
        <p><?php echo htmlspecialchars($product['nome'])?></p>
        <small><?php echo htmlspecialchars($product['emailVenditore']); ?></small><br>
        <a class="detail" href="<?= "/checkProductReportDetail.php?id=".$product["idProdotto"]?>">Vai a segnalazioni <?="(".$product["totalReports"].")" ?></a>
    </div>
<?php } ?>

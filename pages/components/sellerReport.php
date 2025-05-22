<?php function sellerReport($report){ ?>

    <div class="report">
        <p><?php echo ($report['cognome']." ".$report['nome'])?></p>
        <small><?php echo ($report['emailVenditore']); ?></small>
        <small><?php echo ($report['telefono']); ?></small>
        <a class = "detail" href="<?= "/checkSellerReportDetail.php?email=".$report["emailVenditore"]?>">Vai A segnalazioni <?="(".$report["totalReports"].")" ?></a>
    </div>

<?php } ?>

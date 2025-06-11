<?php function sellerReport($report){ ?>
    <div class="report">
        <p><?php echo ($report['cognome']." ".$report['nome'])?></p>
        <small><?php echo htmlspecialchars($report['emailVenditore']); ?></small>
        <small><?php echo htmlspecialchars($report['telefono']); ?></small>
        <a class="detail" href="<?= "/checkSellerReportDetail.php?email=".$report["emailVenditore"]?>">Vai a segnalazioni <?="(".$report["totalReports"].")" ?></a>
    </div>
<?php } ?>

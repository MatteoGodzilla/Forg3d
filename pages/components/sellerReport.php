<?php function sellerReport($report){ ?>
<article class="product">
    <div>
        <p><?php echo ($report['cognome']." ".$report['nome'])?></p>
        <small><?php echo ($report['emailVenditore']); ?></small><br>
        <small><?php echo ($report['telefono']); ?></small>
        <div>
            <a href="<?= "../api/acceptRequest.php?email=".$request["emailUtente"]."&accept=true" ?>">Vai A segnalazioni <?="(".$report["totalReports"].")" ?></a>
        </div>
    </div>
</article>
<?php } ?>

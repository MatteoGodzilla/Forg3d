<?php function productReportDetail($report){ ?>
    <div>
        <b>Segnalato il <?=$report["lastEdit"]?> da:</b>
        <p><?php echo ($report['emailSegnalatore'])?></p>
        <textarea disabled><?php echo ($report['motivo']); ?></textarea><br>
        <a href="/api/hideReport.php?id=<?=$report["id"]."&product=".$report["idProdotto"]?>">Segna come già letta</a>
    </div>
<?php } ?>
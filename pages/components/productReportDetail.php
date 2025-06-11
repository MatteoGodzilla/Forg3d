<?php function productReportDetail($report){ ?>
    <div class="report">
        <b>Segnalato il <?=$report["lastEdit"]?> da:</b>
        <p><?php echo htmlspecialchars($report['emailSegnalatore'])?></p>
        <textarea disabled><?php echo htmlspecialchars($report['motivo']); ?></textarea><br>
        <a class="detail" href="/api/hideReport.php?id=<?=$report["id"]."&product=".$report["idProdotto"]?>">Segna come già letta</a>
    </div>
<?php } ?>
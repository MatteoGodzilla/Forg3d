<?php function sellerReportDetail($report){ ?>
    <div class="report" >
        <b>Segnalato il <?=$report["ultimaModifica"]?> da:</b>
        <p><?php echo htmlspecialchars($report['emailSegnalatore'])?></p>
        <textarea disabled><?php echo htmlspecialchars($report['motivo']); ?></textarea><br>
        <a href="/api/hideReport.php?id=<?=$report["id"]."&seller=".$report["emailVenditore"]?>">Segna come già letta</a>
    </div>
<?php } ?>
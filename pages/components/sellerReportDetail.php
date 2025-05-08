<?php function sellerReportDetail($report){ ?>
    <div>
        <b>Segnalato il <?=$report["ultimaModifica"]?> da:</b>
        <p><?php echo ($report['emailSegnalatore'])?></p>
        <textarea><?php echo ($report['motivo']); ?></textarea><br>
    </div>
<?php } ?>
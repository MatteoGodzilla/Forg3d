<?php function productReportDetail($report){ ?>
    <div>
        <b>Segnalato il <?=$report["lastEdit"]?> da:</b>
        <p><?php echo ($report['emailSegnalatore'])?></p>
        <textarea disabled><?php echo ($report['motivo']); ?></textarea><br>
    </div>
<?php } ?>
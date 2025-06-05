<?php function sellerHomeNotification($notif){ ?>
<div>
    <p><strong> (<?= htmlspecialchars($notif["creazione"]) ?>) <?= htmlspecialchars($notif["titolo"]); ?></strong></p>
    <p>Creata da: <?= $notif["emailVenditore"]!=null ? htmlspecialchars($notif["emailVenditore"]) : "Amministratori"; ?></p>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>

</div>
<?php } ?>

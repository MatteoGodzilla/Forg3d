<?php function sellerHomeNotification($notif){ ?>
<div>
    <p><strong> (<?= htmlspecialchars($notif["creazione"]) ?>) <?= htmlspecialchars($notif["titolo"]); ?></strong></p>
    <p>Creata da: <?= htmlspecialchars($notif["emailVenditore"]); ?></p>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>

</div>
<?php } ?>

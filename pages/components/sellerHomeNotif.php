<?php function sellerHomeNotification($notif){ ?>
<div>
    <p><strong><?= htmlspecialchars($notif["titolo"]); ?></strong></p>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
</div>
<?php } ?>

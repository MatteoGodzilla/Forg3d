<?php function sellerHomeNotification($notif){ ?>
<div>
    <p><?= htmlspecialchars($notif["titolo"]); ?></p>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
</div>
<?php } ?>

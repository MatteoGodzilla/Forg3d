<?php function createNotification($notif){ ?>
<div class="notification" >
    <h3><?= htmlspecialchars($notif["titolo"]); ?></h3>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
    <p>Creata da: <?= $notif["emailVenditore"]!=null ? htmlspecialchars($notif["emailVenditore"]) : "Amministratori"; ?>(<?= htmlspecialchars($notif["creazione"]) ?>)</p>
    <a href="./api/readNotification.php?id=<?= $notif["id"] ?>">
        Segna come letta
        <span class="material-symbols-outlined">check</span>
    </a>
</div>
<?php } ?>

<?php function createNotificationAsRead($notif){ ?>
<div class="notification" >
    <h3><?= htmlspecialchars($notif["titolo"]); ?></h3>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
    <p>Creata da: <?= $notif["emailVenditore"]!=null ? htmlspecialchars($notif["emailVenditore"]) : "Amministratori"; ?>(<?= htmlspecialchars($notif["creazione"]) ?>)</p>
</div>
<?php } ?>


<?php function createNotification($notif){ ?>
<div class="notification" >
    <h3><?= htmlspecialchars($notif["titolo"]); ?></h3>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
    <p>Creata da: <?= $notif["emailMittente"]!=null ? htmlspecialchars($notif["emailMittente"]) : "Amministratori"; ?>(<?= htmlspecialchars($notif["creazione"]) ?>)</p>
    <a class = "read" href="./api/readNotification.php?id=<?= $notif["id"] ?>">
        Segna come letta
        <span class="material-symbols-outlined">check</span>
    </a>
</div>
<?php } ?>

<?php function createNotificationAsRead($notif){ ?>
<div class="notification" >
    <h3><?= htmlspecialchars($notif["titolo"]); ?></h3>
    <p><?= htmlspecialchars($notif["descrizione"]); ?></p>
    <p>Creata da: <?= $notif["emailMittente"]!=null ? htmlspecialchars($notif["emailMittente"]) : "Amministratori"; ?>(<?= htmlspecialchars($notif["creazione"]) ?>)</p>
        <a class ="delete" href="./api/hideNotification.php?id=<?= $notif["id"] ?>">
        Elimina
        <span class="material-symbols-outlined">delete</span>
    </a>
</div>
<?php } ?>

